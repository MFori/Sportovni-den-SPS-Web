<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 19.12.2016
 * Time: 19:02
 */

namespace AppBundle\Form;

class Tournament
{
    private $title;
    private $teams = array();/*
        'A1' => false, 'B1' => false, 'C1' => false,
        'A2' => false, 'B2' => false, 'C2' => false,
        'A3' => false, 'B3' => false, 'C3' => false,
        'A4' => false, 'B4' => false, 'C4' => false,
        );*/
    private $sports = array();

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param array $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return array
     */
    public function getSports()
    {
        return $this->sports;
    }

    /**
     * @param array $sports
     */
    public function setSports($sports)
    {
        $this->sports = array();

        foreach($sports as $k => $sport) {
            $this->sports[$k] = new Sport($sport);
        }
    }
}