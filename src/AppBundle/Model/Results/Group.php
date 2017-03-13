<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 02.01.2017
 * Time: 11:05
 */

namespace AppBundle\Model\Results;

use AppBundle\Entity\Match;
use AppBundle\Entity\Team;
use AppBundle\Model\SimpleGroup;
use RestBundle\Model\RestSerializable;

class Group extends SimpleGroup implements RestSerializable
{
    /**
     * @var Team[]
     */
    private $teams = array();

    /**
     * @var Table
     */
    private $table;

    /**
     * @var Match[]
     */
    private $matches = array();

    public function __construct($group = '')
    {
        $this->group = $group;
    }

    /**
     * @return \AppBundle\Entity\Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param \AppBundle\Entity\Team[] $teams
     */
    public function setTeams($teams)
    {
        $this->teams = array();

        foreach ($teams as $team) {
            $this->addTeam($team);
        }
    }

    /**
     * @param Team $team
     */
    public function addTeam($team)
    {
        if (!$team instanceof Team) {
            $this->teams[] = $team;
        } elseif (!isset($this->teams[$team->getId()])) {
            $this->teams[$team->getId()] = $team;
        }
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param Table $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return \AppBundle\Entity\Match[]
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @param \AppBundle\Entity\Match[] $matches
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * @param Match $match
     */
    public function addMatch(Match $match)
    {
        $this->matches[] = $match;
    }

    public function getTeamsCountToMatches()
    {
        return (1 + sqrt(1 - 4 * 2 * (-sizeof($this->matches)))) / 2;
    }

    public function isComplete()
    {
        foreach ($this->matches as $match) {
            if ($match->getTeam1() === null || $match->getTeam2() === null ||
                $match->getScore1() === null || $match->getScore2() === null
            ) return false;
        }
        return true;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'group' => $this->group,
            'name' => $this->name,
            'teams' => $this->teams,
            'matches' => $this->matches,
            'table' => $this->table
        );
    }
}