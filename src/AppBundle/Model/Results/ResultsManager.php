<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 01.01.2017
 * Time: 19:46
 */

namespace AppBundle\Model\Results;

use AppBundle\Entity\Match;
use AppBundle\Entity\Performance;
use AppBundle\Entity\Scoring;
use AppBundle\Entity\Sport;
use AppBundle\Entity\Team;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;

class ResultsManager
{
    /**
     * @var ResultsManager
     */
    private static $instance = null;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var Tournament
     */
    private $tournament;

    private function __construct(ObjectManager $em, Tournament $tournament)
    {
        $this->em = $em;
        $this->tournament = $tournament;
    }

    /**
     * @param ObjectManager|null $em
     * @param Tournament|null $tournament
     * @return ResultsManager
     * @throws \Exception
     */
    public static function getInstance(ObjectManager $em = null, Tournament $tournament = null)
    {
        if (self::$instance == null) {

            if ($em == null || $tournament == null)
                throw new \Exception('First initialize manager.');

            self::$instance = new ResultsManager($em, $tournament);
        }

        return self::$instance;
    }

    public function getCompleteResults()
    {
        $teams = $this->getTeamsArray();
        $sports = $this->em->getRepository('AppBundle:Sport')->findBy(array('active' => true));
        $pointsArray = array();
        $SportTables = array();
        $TeamTables = array();
        /* @var $sport Sport */
        foreach ($sports as $sport) {
            $pointsArray[$sport->getId()] = array(
                'sport' => $sport,
                'points' => 0,
                'position' => 0
            );
            $scoring = $this->getScoring($sport);
            switch ($scoring->getType()->getId()) {
                case TYPE_INDIVIDUALS:
                    $SportTables[$sport->getId()] = $this->getIndividualsTable($sport)['table'];
                    break;
                case TYPE_ROBIN:
                    $SportTables[$sport->getId()] = $this->getRobinTable($sport, $scoring)['table'];
                    break;
                case TYPE_GROUP_FINALE:
                case TYPE_GROUP_GROUP:
                    $SportTables[$sport->getId()] = $this->groupsToOneTable($this->getGroupData($sport, $scoring)['groups'], $scoring);
                    break;
            }
        }

        foreach ($teams as $team) {
            $TeamTables[$team->getId()]['team'] = $team;
            $TeamTables[$team->getId()]['points'] = $TeamTables[$team->getId()]['position'] = 0;
            $TeamTables[$team->getId()]['sports'] = $pointsArray;
        }

        /* @var $table Table */
        foreach ($SportTables as $sportId => $table) {
            foreach ($table->getLines() as $line) {
                $TeamTables[$line->getTeam()->getId()]['sports'][$sportId]['points'] = $line->getSportPoints();
                $TeamTables[$line->getTeam()->getId()]['sports'][$sportId]['position'] = $line->getPosition();
                $TeamTables[$line->getTeam()->getId()]['points'] += $line->getSportPoints();
            }
        }

        usort($TeamTables, array($this, 'compareComplete'));
        $position = 1;
        foreach ($TeamTables as $k => $team_table) {
            $TeamTables[$k]['sports'] = array_values($team_table['sports']);

            if ($k != 0 && $team_table['points'] == $TeamTables[$k - 1]['points']) {
                $TeamTables[$k]['position'] = $TeamTables[$k - 1]['position'];
            } else {
                $TeamTables[$k]['position'] = $position;
            }

            $position++;
        }

        return array_values($TeamTables);
    }

    /**
     * @param Sport $sport
     * @return null|array
     */
    public function getSportResults(Sport $sport)
    {
        $scoring = $this->getScoring($sport);
        switch ($scoring->getType()->getId()) {
            case TYPE_INDIVIDUALS:
                return $this->getIndividualsTable($sport);
            case TYPE_ROBIN:
                return $this->getRobinTable($sport, $scoring);
            case TYPE_GROUP_FINALE:
            case TYPE_GROUP_GROUP:
                return $this->getGroupData($sport, $scoring);
        }

        return null;
    }

    /**
     * @param Sport $sport
     * @return Table
     */
    private function getIndividualsTable(Sport $sport)
    {
        $performances = $this->em->getRepository('AppBundle:Performance')->findBy(array(
            'sport' => $sport,
            'tournament' => $this->tournament
        ));
        $teams = $this->getTeamsArray();
        /* @var TableLine[] $lines */
        $lines = array();

        foreach ($teams as $team) {
            $line = new TableLine();
            $line->setTeam($team);
            $line->setPoints(0);
            $lines[$team->getId()] = $line;
        }

        /* @var $performance Performance */
        foreach ($performances as $performance) {
            $lines[$performance->getTeam()->getId()]
                ->setPoints($lines[$performance->getTeam()->getId()]->getPoints() + $performance->getPoints());
        }

        $table = new Table();
        foreach ($lines as $line) {
            $table->addLine($line);
        }
        return array('table' => $table);
    }

    /**
     * @param Sport $sport
     * @param Scoring $scoring
     * @param Group $group
     * @return array
     */
    private function getRobinTable(Sport $sport, Scoring $scoring, $group = null)
    {
        if ($group == null) {
            $matches = $this->em->getRepository('AppBundle:Match')->findBy(array(
                'sport' => $sport,
                'tournament' => $this->tournament
            ));
            $teams = $this->getTeamsArray();
        } else {
            $matches = $group->getMatches();
            $teams = $group->getTeams();
        }
        /* @var TableLine[] $lines */
        $lines = array();

        foreach ($teams as $team) {
            $line = new TableLine();
            $line->setTeam($team);
            $line->setPoints(0);
            if ($team instanceof Team) {
                if (isset($lines[$team->getId()])) {
                    $lines[] = $lines[$team->getId()];
                }
                $lines[$team->getId()] = $line;
            } else {
                $lines[] = $line;
            }
        }

        /* @var $match Match */
        foreach ($matches as $match) {
            if ($match->getScore1() === null || $match->getScore2() === null) continue;
            if ($match->getScore1() > $match->getScore2()) {
                $lines[$match->getTeam1()->getId()]->addPoints($scoring->getWin());
                $lines[$match->getTeam2()->getId()]->addPoints($scoring->getLose());
            } else {
                $lines[$match->getTeam2()->getId()]->addPoints($scoring->getWin());
                $lines[$match->getTeam1()->getId()]->addPoints($scoring->getLose());
            }
        }

        $table = new Table();
        foreach ($lines as $line) {
            $table->addLine($line);
        }
        usort($matches, array($this, 'compareMatches'));
        return array('table' => $table, 'matches' => $matches);
    }

    /**
     * @param Sport $sport
     * @param Scoring $scoring
     * @return array
     */
    private function getGroupData(Sport $sport, Scoring $scoring)
    {
        //$teams = $this->getTeamsArray();
        $matches = $this->em->getRepository('AppBundle:Match')->findBy(array(
            'sport' => $sport,
            'tournament' => $this->tournament
        ));
        $groups = $this->getGroups($matches);
        foreach ($groups as $group) {
            $group->setTable($this->getRobinTable($sport, $scoring, $group)['table']);
        }

        return array('groups' => $groups);
    }

    /**
     * @return Team[]
     */
    private function getTeamsArray()
    {
        $teams_ = $this->em->getRepository('AppBundle:Team')->findBy(array('active' => true));
        $teams = array();
        /* @var $team Team */
        foreach ($teams_ as $team) {
            $teams[$team->getId()] = $team;
        }

        return $teams;
    }

    /**
     * @param Sport $sport
     * @return Group[]
     */
    public function preupdate(Sport $sport)
    {
        $scoring = $this->getScoring($sport);
        if ($scoring->getType()->getId() == TYPE_INDIVIDUALS || $scoring->getType()->getId() == TYPE_ROBIN) return;

        return $this->getGroupData($sport, $scoring)['groups'];
    }

    /**
     * @param Sport $sport
     * @param $previous Group[]
     */
    public function update(Sport $sport, $previous)
    {
        $scoring = $this->getScoring($sport);
        if ($scoring->getType()->getId() == TYPE_INDIVIDUALS || $scoring->getType()->getId() == TYPE_ROBIN) return;

        /* @var $finaleGroups Group[] */
        $finaleGroups = $this->getGroupData($sport, $scoring)['groups'];
        /* @var $coreGroups Group[] */
        $coreGroups = array();

        foreach ($finaleGroups as $k => $group) {
            if ($group->getMatches()[0]->getCoreGroup()) {
                $coreGroups[] = $group;
                unset($finaleGroups[$k]);
            }
        }
        $finaleGroups = array_values($finaleGroups);
        usort($finaleGroups, array($this, 'compareGroupsByPriority'));

        $previousGroups = array();
        foreach ($previous as $group) {
            $previousGroups[$group->getGroup()] = $group;
        }

        foreach ($coreGroups as $group) {
            if (!$group->isComplete()) {
                $this->removeGroupFromFinales($group, $finaleGroups);
            } else {
                $this->addGroupToFinales($group, $finaleGroups, $previousGroups[$group->getGroup()]);
            }
        }
    }

    /**
     * @param Group $group
     * @param $finales Group[]
     * @param $previousGroup Group
     */
    private function addGroupToFinales(Group $group, $finales, $previousGroup)
    {
        $group_teams = $previousGroup->getTeams();
        $lines = $group->getTable()->getLines();
        foreach ($lines as $line) {
            if(!isset($finales[$this->getFinaleGroupIndex($line->getPosition(), $finales)])) continue;
            $finale = $finales[$this->getFinaleGroupIndex($line->getPosition(), $finales)];
            if ($this->isTeamInGroup($finale, $line->getTeam())) continue;
            $rivals = array();
            $finaleMatches = $finale->getMatches();
            usort($finaleMatches, array($this, 'compareMatchesByEmpty'));

            foreach ($finale->getMatches() as $k => $match) {
                if (sizeof($rivals) == sizeof($finale->getTeams()) - 1) continue;

                if ($match->getTeam1() != $line->getTeam() && in_array($match->getTeam1(), $group_teams)) {
                    $match->setTeam1(null)->setScore1(null)->setScore2(null);
                }
                if ($match->getTeam2() != $line->getTeam() && in_array($match->getTeam2(), $group_teams)) {
                    $match->setTeam2(null)->setScore1(null)->setScore2(null);
                }

                if ($match->getTeam1() !== null && $match->getTeam2() !== null) continue;

                if ($match->getTeam1() === null && $match->getTeam2() === null) {
                    $match->setTeam1($line->getTeam());
                    $rivals[] = new Team();
                } elseif ($match->getTeam2() === null && !in_array($match->getTeam1(), $rivals)) {
                    $match->setTeam2($line->getTeam());
                    $rivals[] = $match->getTeam1();
                } elseif ($match->getTeam1() === null && !in_array($match->getTeam2(), $rivals)) {
                    $match->setTeam1($line->getTeam());
                    $rivals[] = $match->getTeam2();
                }
            }
        }
    }

    /**
     * @param Group $group
     * @param $finales Group[]
     */
    private function removeGroupFromFinales(Group $group, $finales)
    {
        $teams = $group->getTeams();
        foreach ($finales as $finale) {
            foreach ($finale->getMatches() as $match) {
                if (in_array($match->getTeam1(), $teams)) {
                    $match->setTeam1(null)
                        ->setScore1(null)
                        ->setScore2(null);
                } elseif (in_array($match->getTeam2(), $teams)) {
                    $match->setTeam2(null)
                        ->setScore1(null)
                        ->setScore2(null);
                }
            }
        }
    }

    /**
     * @param $position
     * @param $finaleGroups
     * @return int|null
     */
    private function getFinaleGroupIndex($position, $finaleGroups)
    {
        $position -= 1;
        if (isset($finaleGroups[$position])) {
            return $position;
        } else return null;
    }

    /**
     * @param Group $group
     * @param Team $team
     * @return bool
     */
    private function isTeamInGroup(Group $group, Team $team)
    {
        foreach ($group->getTeams() as $gteam) {
            if ($gteam == $team) return true;
        }
        return false;
    }

    /**
     * @param Sport $sport
     * @return Scoring
     */
    private function getScoring(Sport $sport)
    {
        return $this->em->getRepository('AppBundle:Scoring')->findOneBy(array(
            'sport' => $sport,
            'tournament' => $this->tournament
        ));
    }

    /**
     * @param $matches Match[]
     * @return Group[]
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

        usort($groups, array($this, 'compareGroupsByPriority'));

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

        return $groups;
    }

    /**
     * @param $groups Group[]
     * @param $scoring Scoring
     * @return Table
     */
    private function groupsToOneTable($groups, $scoring)
    {
        $teams = $this->getTeamsArray();
        switch ($scoring->getType()->getId()) {
            case TYPE_GROUP_FINALE:
                return $this->finaleGroupsToOneTable($groups, $teams);
            case TYPE_GROUP_GROUP:
                return $this->finaleGroupsToOneTable($groups, $teams);
                break;
        }

        return null;
    }

    /**
     * @param $groups Group[]
     * @param $teams Team []
     * @return Table
     */
    private function finaleGroupsToOneTable($groups, $teams)
    {
        $table = new Table();
        /* @var $coreGroups Group[] */
        $coreGroups = array();
        $points = sizeof($teams);
        usort($groups, array($this, 'compareGroupsByPriority'));

        foreach ($groups as $group) {
            if (!$group->getMatches()[0]->getCoreGroup()) {
                $lines = $group->getTable()->getLines();
                for ($i = 0; $i < sizeof($lines); $i++) {
                    if ($lines[$i]->getTeam() != null) {
                        $line = $lines[$i];
                        $line->setPoints($points);
                        $table->addLine($line);
                        $points--;
                    }
                }
            } else {
                $coreGroups[] = $group;
            }
        }

        $i = 0;
        while (sizeof($table->getLines(false)) != sizeof($teams)) {
            $t = new Table();
            for ($j = 0; $j < sizeof($coreGroups); $j++) {
                $lines = $coreGroups[$j]->getTable()->getLines(true);
                if (isset($lines[$i]) && !$table->hasTeam($lines[$i]->getTeam())) {
                    $line = clone $lines[$i];
                    if ($line->getMatchesCount() != 0) {
                        $line->setPoints($points);
                        $points--;
                    } else {
                        $line->setPoints(0);
                    }
                    $t->addLine($line);
                }
            }

            $lines = $t->getLines(false);
            foreach ($lines as $line) {
                $table->addLine($line);
            }

            $i++;
        }

        return $table;
    }

    /**
     * @param Match $a
     * @param Match $b
     * @return int
     */
    private function compareMatches(Match $a, Match $b)
    {
        if ($a->getDate() == $b->getDate()) return 0;
        return $a->getDate() > $b->getDate() ? -1 : 1;
    }

    /**
     * @param Match $a
     * @param Match $b
     * @return int
     */
    private function compareMatchesByEmpty(Match $a, Match $b)
    {
        $acount = $bcount = 2;
        if ($a->getTeam1() == null && $a->getTeam2() == null) $acount = 0;
        else if ($a->getTeam1() == null || $a->getTeam2() == null) $acount = 1;
        if ($b->getTeam1() == null && $b->getTeam2() == null) $bcount = 0;
        else if ($b->getTeam1() == null || $b->getTeam2() == null) $bcount = 1;

        if ($acount == $bcount) return 0;
        return $acount > $bcount ? -1 : 1;
    }

    /**
     * @param Group $a
     * @param Group $b
     * @return int
     */
    private function compareGroups(Group $a, Group $b)
    {
        return $a->getName() > $b->getName() ? 1 : -1;
    }

    /**
     * @param Group $a
     * @param Group $b
     * @return int
     */
    private function compareGroupsByPriority(Group $a, Group $b)
    {
        return $a->getGroup() > $b->getGroup() ? -1 : 1;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private function compareComplete($a, $b)
    {
        return $a['points'] > $b['points'] ? -1 : 1;
    }
}