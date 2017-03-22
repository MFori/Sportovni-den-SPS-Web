<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 21.12.2016
 * Time: 19:02
 */

namespace AppBundle\Model;

use AppBundle\Entity\Match;
use AppBundle\Entity\MatchStatus;
use AppBundle\Entity\Sport;
use AppBundle\Entity\Team;
use AppBundle\Entity\Tournament;

/**
 * Class MatchesGenerator
 * @package AppBundle\Model
 */
abstract class MatchesGenerator
{
    /**
     * Tournament
     * @var Tournament
     */
    protected $tournament;
    /**
     * Sport
     * @var Sport
     */
    protected $sport;
    /**
     * Teams
     * @var array
     */
    protected $teams = array();
    /**
     * Match status
     * @var MatchStatus
     */
    protected $defaultStatus;

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Static method for creating new generator
     * @param $systemType
     * @return FinaleGenerator|GroupGenerator|RobinGenerator|null
     */
    public static function CreateGenerator($systemType)
    {
        switch ($systemType) {
            case TYPE_GROUP_FINALE:
                return new FinaleGenerator();
            case TYPE_GROUP_GROUP:
                return new GroupGenerator();
            case TYPE_ROBIN:
                return new RobinGenerator();
        }

        return null;
    }

    /**
     * Generate matches
     *
     * @return array
     */
    public abstract function generate();

    /**
     * Create robin matches
     * @param $teams
     * @param array $params
     * @return array
     */
    protected function createRobinMatches($teams, $params = array())
    {
        shuffle($teams);
        $teamsSize = sizeof($teams);                                    // number of teams
        $rounds = ($teamsSize % 2 == 0) ? $teamsSize - 1 : $teamsSize;  // number of rounds
        $matches = array();

        if($rounds==1) {
            return array($this->createMatch($teams[0], $teams[1], $params));
        }

        if (sizeof($teams) % 2 != 0) {
            $teams[] = null;
        }

        $topRow = $downRow = array();
        for ($i = 0; $i < sizeof($teams); $i++) {
            if ($i < sizeof($teams) / 2) {
                $topRow[] = $teams[$i];
            } else {
                $downRow[] = $teams[$i];
            }
        }

        for ($i = 1; $i <= $rounds; $i++) {

            // If round is odd change position of fixed team
            if ($i % 2 == 0) {
                $fixed = $topRow[0];
                $topRow[0] = $downRow[0];
                $downRow[0] = $fixed;
            }

            for ($j = 0; $j < sizeof($topRow); $j++) {

                // If match is odd change home team
                if (($j + 1) % 2 == 0) {
                    $home = $topRow[$j];
                    $topRow[$j] = $downRow[$j];
                    $downRow[$j] = $home;
                }

                $match = $this->createMatch($topRow[$j], $downRow[$j], $params);
                if ($match != null) {
                    $matches[] = $match;
                }

                // If match is odd return home team
                if ($j % 2 == 0) {
                    $home = $topRow[$j];
                    $topRow[$j] = $downRow[$j];
                    $downRow[$j] = $home;
                }
            }

            // If round is odd return fixed team at first position
            if ($i % 2 == 0) {
                $fixed = $topRow[0];
                $topRow[0] = $downRow[0];
                $downRow[0] = $fixed;
            }

            list($downRow, $topRow) = $this->rotateMatches($topRow, $downRow);
        }

        return $matches;
    }

    /**
     * Rotate teams rows
     *
     * @param $topRow array
     * @param $downRow array
     * @return array
     */
    private function rotateMatches($topRow, $downRow)
    {
        $tempTop = $topRow;
        $tempDown = $downRow;

        for ($i = 0; $i < sizeof($topRow); $i++) {
            if ($i != 0) {
                if (array_key_exists($i + 1, $topRow))
                    $topRow[$i + 1] = $tempTop[$i];
                else $downRow[sizeof($downRow) - 1] = $tempTop[$i];
            }

            if (array_key_exists($i - 1, $downRow))
                $downRow[$i - 1] = $tempDown[$i];
            else $topRow[1] = $tempDown[$i];
        }

        return array($topRow, $downRow);
    }

    /**
     * Create match
     *
     * @param $team1 Team|null
     * @param $team2 Team|null
     * @param $params array
     * @return Match|null
     */
    private function createMatch($team1, $team2, $params)
    {
        if ($team1 instanceof Team && $team2 instanceof Team) {
            $match = $this->createDefault();
            $match->setTeam1($team1)->setTeam2($team2);
            if (isset($params['group'])) $match->setGroup($params['group']);
            if (isset($params['core_group'])) $match->setCoreGroup($params['core_group']);
            if (isset($params['status'])) $match->setStatus($params['status']);

            return $match;
        }

        return null;
    }

    /**
     * Set tournament
     *
     * @param $tournament Tournament
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * Set sport
     *
     * @param $sport Sport
     */
    public function setSport($sport)
    {
        $this->sport = $sport;
    }

    /**
     * Add team
     *
     * @param $team Team
     */
    public function addTeam($team)
    {
        $this->teams[] = $team;
    }

    /**
     * Set defaultStatus
     *
     * @param MatchStatus $defaultStatus
     */
    public function setDefaultStatus($defaultStatus)
    {
        $this->defaultStatus = $defaultStatus;
    }

    /**
     * Get team year
     *
     * @param $team Team
     * @return int
     */
    protected function getTeamYear($team)
    {
        if ($team->getId() <= 3) return 1;
        elseif ($team->getId() > 3 && $team->getId() <= 6) return 2;
        elseif ($team->getId() > 6 && $team->getId() <= 9) return 3;
        else return 4;
    }

    /**
     * Create default match
     *
     * @return Match
     */
    public function createDefault()
    {
        $m = new Match();
        $m->setSport($this->sport);
        $m->setTournament($this->tournament);
        $m->setStatus($this->defaultStatus);

        return $m;
    }
}