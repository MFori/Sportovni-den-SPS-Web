<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 01.01.2017
 * Time: 22:12
 */

namespace AppBundle\Model\Results;

use AppBundle\Entity\Team;
use RestBundle\Model\RestSerializable;

class TableLine implements RestSerializable
{
    /**
     * @var Team
     */
    private $team;
    /**
     * @var int
     */
    private $points = 0;
    /**
     * @var int
     */
    private $position;
    /**
     * @var int
     */
    private $sportPoints;
    /**
     * @var int
     */
    private $matchesCount = 0;

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    public function addPoints($points)
    {
        $this->points += $points;
        $this->matchesCount++;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getSportPoints()
    {
        return $this->sportPoints;
    }

    /**
     * @param int $sportPoints
     */
    public function setSportPoints($sportPoints)
    {
        $this->sportPoints = $sportPoints;
    }

    /**
     * @return int
     */
    public function getMatchesCount()
    {
        return $this->matchesCount;
    }

    /**
     * @param int $matchesCount
     */
    public function setMatchesCount($matchesCount)
    {
        $this->matchesCount = $matchesCount;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'team' => $this->team,
            'points' => $this->points,
            'position' => $this->position,
            'sport_points' => $this->sportPoints,
        );
    }
}