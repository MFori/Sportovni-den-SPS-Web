<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 21.12.2016
 * Time: 18:10
 */

namespace AppBundle\Model;

use AppBundle\Entity\MatchStatus;
use AppBundle\Entity\ScoringType;
use AppBundle\Entity\Sport;
use AppBundle\Entity\Team;
use AppBundle\Entity\Tournament;
use AppBundle\Form\Tournament as Data;

class TournamentBuilder
{
    /**
     * @var Tournament
     */
    private $data;
    /**
     * @var \AppBundle\Entity\Tournament
     */
    private $tournament;
    /**
     * @var array
     */
    private $teams;
    /**
     * @var array
     */
    private $sports;
    /**
     * @var array
     */
    private $sportEntities;
    /**
     * @var array
     */
    private $scoring;
    /**
     * @var array
     */
    private $matches = array();
    /**
     * @var array
     */
    private $performances;
    /**
     * @var array
     */
    private $scoringTypes;
    /**
     * @var MatchStatus
     */
    private $matchStatus;

    public function __construct(Data $formData, Tournament $tournament, $sports, $statuses)
    {
        $this->data = $formData;
        $this->tournament = $tournament;

        if(!is_int($tournament->getId())) throw new \BadMethodCallException('Save tournament at first.');

        /* @var $sport Sport */
        foreach($sports as $sport) {
            $this->sportEntities[$sport->getId()] = $sport;
        }

        /* @var $status MatchStatus */
        foreach($statuses as $status) {
            if($status->hasStatus(STATUS_CREATED)){
                $this->matchStatus = $status;
                break;
            }
        }
    }

    /**
     * @return bool
     */
    public function create()
    {
        $this->updateTeams();
        $this->mineSports();
        $this->createScoring();
        $this->createMatches();

        return true;
    }

    /**
     *
     */
    private function updateTeams()
    {
        $this->teams = $this->data->getTeams();
    }

    /**
     *
     */
    private function mineSports()
    {
        $this->sports = array();

        $sports = $this->data->getSports();
        /* @var $sport \AppBundle\Form\Sport */
        foreach ($sports as $sport) {
            $this->sports[$sport->getId()] = $sport;
        }

        /* @var $sport Sport */
        foreach ($this->sportEntities as $sport) {
            /* @var $data \AppBundle\Form\Sport */
            $data = $this->sports[$sport->getId()];

            $sport->setActive($data->getActive());
        }
    }

    /**
     *
     */
    private function createMatches()
    {
        /* @var $sport \AppBundle\Form\Sport */
        foreach ($this->sports as $sport) {
            if (!$sport->getActive()) continue;
            $generator = MatchesGenerator::CreateGenerator($sport->getScoringType());
            if ($generator == null) continue;

            $generator->setTournament($this->tournament);
            $generator->setSport($this->sportEntities[$sport->getId()]);
            $generator->setDefaultStatus($this->matchStatus);

            /* @var $team Team */
            foreach ($this->teams as $team) {
                if ($team->getActive())
                    $generator->addTeam($team);
            }

            $this->matches[$sport->getId()] = $generator->generate();
        }
    }

    /**
     *
     */
    private function createScoring()
    {
        /* @var $sport \AppBundle\Form\Sport */
        foreach ($this->sports as $sport) {
            if (!$sport->getActive()) continue;
            $scoring = $sport->createScoring();
            $scoring->setTournamentId($this->tournament->getId());
            $scoring->setTournament($this->tournament);
            $scoring->setSport($this->sportEntities[$sport->getId()]);
            $scoring->setType($this->scoringTypes[$scoring->getTypeId()]);

            $this->scoring[$sport->getId()] = $scoring;
        }
    }

    /**
     * @return mixed
     */
    public function getSports()
    {
        return $this->sportEntities;
    }

    /**
     * @return \AppBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @return array
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return array
     */
    public function getScoring()
    {
        return $this->scoring;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return array
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * @param array $scoringTypes
     */
    public function setScoringTypes($scoringTypes)
    {
        /* @var $type ScoringType */
        foreach($scoringTypes as $type)
        {
            $this->scoringTypes[$type->getId()] = $type;
        }
    }
}