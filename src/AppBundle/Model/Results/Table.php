<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 01.01.2017
 * Time: 22:10
 */

namespace AppBundle\Model\Results;

use AppBundle\Entity\Team;
use RestBundle\Model\RestSerializable;

class Table implements RestSerializable
{
    /**
     * @var TableLine[]
     */
    private $lines = array();

    private $sort = true;

    /**
     * @param bool|true $sort
     */
    public function __construct($sort = true)
    {
        $this->sort = $sort;
    }

    /**
     * @param bool|true $sort
     * @return TableLine[]
     */
    public function getLines($sort = true)
    {
        if ($sort) {
            $points = sizeof($this->lines);
            $position = 1;
            $lines = $this->lines;
            usort($lines, array($this, 'compareLines'));
            foreach ($lines as $line) {
                $line->setSportPoints($line->getPoints() != 0 ? $points : 0);
                $line->setPosition($position);
                $points--;
                $position++;
            }

            return $lines;
        }

        return $this->lines;
    }

    /**
     * @param Team $team
     * @return int|null
     */
    public function getTeamPosition(Team $team)
    {
        $lines = $this->getLines(true);
        foreach ($lines as $line) {
            if ($line->getTeam() == $team) return $line->getPosition();
        }

        return null;
    }

    public function hasTeam(Team $team)
    {
        $lines = $this->getLines(true);
        foreach ($lines as $line) {
            if ($line->getTeam() == $team) return true;
        }

        return false;
    }

    /**
     * @param $position
     * @return TableLine|null
     */
    public function getLineAtPosition($position)
    {
        $lines = $this->getLines(true);
        return isset($lines[$position]) ? $lines[$position] : null;
    }

    /**
     * @param TableLine $line
     */
    public function addLine(TableLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * @param array $lines
     */
    public function setLines($lines = array())
    {
        $this->lines = $lines;
    }

    /**
     * @param TableLine $a
     * @param TableLine $b
     * @return int
     */
    private function compareLines(TableLine $a, TableLine $b)
    {
        if ($a->getPoints() == $b->getPoints()) {
            if ($a->getMatchesCount() == $b->getMatchesCount()) {
                return 0;
            }
            return $a->getMatchesCount() < $b->getMatchesCount() ? -1 : 1;
        }
        return $a->getPoints() < $b->getPoints() ? 1 : -1;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return $this->getLines($this->sort);
    }
}