<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 30.12.2016
 * Time: 12:06
 */

namespace RestBundle\Controller;

use AppBundle\Entity\Match;
use AppBundle\Entity\MatchStatus;
use AppBundle\Model\Results\Group;
use AppBundle\Model\Results\ResultsManager;
use AppBundle\Model\SimpleGroup;
use RestBundle\Model\Exception\BadRequestException;
use RestBundle\Model\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MatchesController
 * @package RestBundle\Controller
 */
class MatchesController extends RestController
{
    /**
     * Route for Rest request of matches/match
     * @Route("/matches/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     * @Method("GET")
     * @param $id
     * @return \RestBundle\Model\RestResponse
     * @throws \RestBundle\Model\Exception\OutOfDateException
     * @throws NotFoundException
     */
    protected function getMatches($id = null)
    {
        if ($id != null) {
            $match = $this->getDoctrine()->getRepository('AppBundle:Match')->find($id);
            if (!$match instanceof Match) throw new NotFoundException;
            return $this->createResponse(array('match' => $match));
        } else {
            /* @var $matches Match[] */
            $matches = $this->getRepositoryWithCriteria('AppBundle:Match');
            $groups = $this->getGroups($matches);

            return $this->createResponse(array('matches' => $matches, 'groups' => $groups));
        }
    }

    /**
     * Get groups
     * @param $matches Match[]
     * @return \AppBundle\Model\SimpleGroup[]
     */
    private function getGroups($matches)
    {
        /* @var Group[] $groups */
        $groups = array();

        foreach ($matches as $match) {
            if (!isset($groups[$match->getGroup()])) {
                $groups[$match->getGroup()] = new Group($match->getGroup());
            }

            $groups[$match->getGroup()]->addMatch($match);
            if ($match->getTeam1() != null)
                $groups[$match->getGroup()]->addTeam($match->getTeam1());
            if ($match->getTeam2() != null)
                $groups[$match->getGroup()]->addTeam($match->getTeam2());
        }

        foreach ($groups as $group) {
            $teamsCount = sizeof($group->getTeams());
            $requiredCount = $group->getTeamsCountToMatches();
            for ($i = 0; $i < $requiredCount - $teamsCount; $i++) {
                $group->addTeam(null);
            }
        }

        usort($groups, array($this, 'compareGroups'));

        $positions = 0;
        for ($i = 0; $i < sizeof($groups); $i++) {
            if ($groups[$i]->getMatches()[0]->getCoreGroup()) {
                $groups[$i]->setName('Skupina ' . $groups[$i]->getGroup());
                continue;
            }
            if ($positions == 0) {
                $groups[$i]->setName('Finále');
            } else {
                $groups[$i]->setName('O ' . ($positions + 1) . '. místo');
            }
            $positions += sizeof($groups[$i]->getTeams());
        }

        $res = array();
        foreach ($groups as $group) {
            $res[] = new SimpleGroup($group->getGroup(), $group->getName());
        }

        return $res;
    }

    /**
     * Compare groups by group
     * @param SimpleGroup $a
     * @param SimpleGroup $b
     * @return int
     */
    private function compareGroups(SimpleGroup $a, SimpleGroup $b)
    {
        return $a->getGroup() > $b->getGroup() ? -1 : 1;
    }

    /**
     * Route for Rest request of updating match/matches
     * @Route("/matches/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     * @Method("PUT")
     * @param $id
     * @param Request $request
     * @return \RestBundle\Model\RestResponse
     * @throws \RestBundle\Model\Exception\OutOfDateException
     * @throws NotFoundException
     */
    protected function updateMatches($id = null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $matches Match[] */
        $matches = array();
        $manager = ResultsManager::getInstance($em, $this->getActiveTournament());

        if ($id != null) {
            $match = $em->getRepository('AppBundle:Match')->find($id);
            if (!$match instanceof Match) throw new NotFoundException;
            $matches[] = $match;
        } else {
            $matches = $this->getRepositoryWithCriteria('AppBundle:Match');
        }

        $previousMatches = array();
        if (isset($matches[0])) {
            $previousMatches = $manager->preupdate($matches[0]->getSport());
        }
        $data = $request->request->all();

        /* @var $match Match */
        foreach ($matches as $match) {
            $this->updateMatch($match, $data);
        }

        if (isset($matches[0])) {
            $manager->Update($match->getSport(), $previousMatches);
        }

        $em->flush();

        return $this->createResponse();
    }

    /**
     * Update match
     * @param Match $match
     * @param array $data
     * @throws BadRequestException
     */
    private function updateMatch(Match $match, $data = array())
    {
        if (isset($data['score_1'])) {
            $match->setScore1($data['score_1'] != 'null' ? $data['score_1'] : null);
        }
        if (isset($data['score_2'])) {
            $match->setScore2($data['score_2'] != 'null' ? $data['score_2'] : null);
        }
        if (isset($data['status'])) {
            $status = $this->getDoctrine()->getRepository('AppBundle:MatchStatus')->find($data['status']);
            if (!$status instanceof MatchStatus) throw new BadRequestException;
            $match->setStatus($status);
        }
        $match->setReferee($this->getUser());
        $match->setDate(new \DateTime());
    }
}