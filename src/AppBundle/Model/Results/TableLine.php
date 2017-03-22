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

/**
 * Class TableLine
 * @package AppBundle\Model\Results
 */
class TableLine implements RestSerializable
{
    /**
     * Team
     * @var Team
     */
    private $team;
    /**
     * Points
     * @var int
     */
    private $points = 0;
    /**
     * Position
     * @var int
     */
    private $position;
    /**
     * Sport points
     * @var int
     */
    private $sportPoints;
    /**
     * Matches count
     * @var int
     */
    private $matchesCount = 0;

    /**
     * Get team
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set team
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * Get points
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set points
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * Add points
     * @param int $points
     */
    public function addPoints($points)
    {
        $this->points += $points;
        $this->matchesCount++;
    }

    /**
     * Get points
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get sport points
     * @return int
     */
    public function getSportPoints()
    {
        return $this->sportPoints;
    }

    /**
     * Set sport points
     * @param int $sportPoints
     */
    public function setSportPoints($sportPoints)
    {
        $this->sportPoints = $sportPoints;
    }

    /**
     * Get matches count
     * @return int
     */
    public function getMatchesCount()
    {
        return $this->matchesCount;
    }

    /**
     * Set matches count
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