<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 30.12.2016
 * Time: 12:07
 */

namespace RestBundle\Controller;

use AppBundle\Entity\Scoring;
use AppBundle\Entity\Sport;
use AppBundle\Model\Results\ResultsManager;
use AppBundle\Model\Results\TimeResult;
use RestBundle\Model\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ResultsController
 * @package RestBundle\Controller
 */
class ResultsController extends RestController
{
    /**
     * @Route("/results/timeline")
     * @Method("GET")
     * @return \RestBundle\Model\RestResponse
     */
    public function timeResultsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT m FROM AppBundle:Match m WHERE m.tournament = :tourney'.
        ' AND m.score1 is not null AND m.score2 is not null')
            ->setParameter('tourney', $this->getActiveTournament()->getId());
        $results = $query->getResult();
        $results = array_merge($results, (array) $this->getDoctrine()->getRepository('AppBundle:Performance')->findBy(
            array('tournament' => $this->getActiveTournament()),
            array('date' => 'DESC')
        ));

        usort($results, array($this, 'compareTimeResults'));

        return $this->createResponse(array('results' => $results));
    }

    /**
     * @param TimeResult $a
     * @param TimeResult $b
     * @return int
     */
    private function compareTimeResults(TimeResult $a, TimeResult $b)
    {
        return $a->getDate() > $b->getDate() ? -1 : 1;
    }

    /**
     * @Route("/results/complete")
     * @Method("GET")
     * @return \RestBundle\Model\RestResponse
     */
    public function completeResultsAction()
    {
        $manager = ResultsManager::getInstance($this->getDoctrine()->getManager(), $this->getActiveTournament());
        $results = $manager->getCompleteResults();

        return $this->createResponse(array('results' => $results));
    }

    /**
     * @Route("/results/{sport_id}", requirements={"sport_id": "\d+"})
     * @Method("GET")
     * @param int $sport_id
     * @return \RestBundle\Model\RestResponse
     * @throws NotFoundException
     */
    public function sportsResultsAction($sport_id)
    {
        $sport = $this->getDoctrine()->getRepository('AppBundle:Sport')->findOneBy(array(
            'id' => $sport_id,
            'active' => true
        ));

        if(!$sport instanceof Sport) throw new NotFoundException;

        $scoring = $this->getDoctrine()->getRepository('AppBundle:Scoring')->findOneBy(array(
            'tournament' => $this->getActiveTournament(),
            'sport' => $sport
        ));

        if(!$scoring instanceof Scoring) throw new NotFoundException;

        $manager = ResultsManager::getInstance($this->getDoctrine()->getManager(), $this->getActiveTournament());
        $results = $manager->getSportResults($sport);

        return $this->createResponse(array('type' => $scoring->getType()->getId(), 'results' => $results));
    }
}