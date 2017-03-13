<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 15.12.2016
 * Time: 18:36
 */

namespace AppBundle\Entity;

use AppBundle\Model\Results\TimeResult;
use Doctrine\ORM\Mapping as ORM;
use RestBundle\Model\RestSerializable;

/**
 * Class Performance
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="performance")
 */
class Performance implements RestSerializable, TimeResult
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="team")
     */
    private $team_id;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    private $team;

    /**
     * @ORM\Column(type="integer", name="sport")
     */
    private $sport_id;

    /**
     * @ORM\ManyToOne(targetEntity="Sport")
     * @ORM\JoinColumn(name="sport", referencedColumnName="id")
     */
    private $sport;

    /**
     * @ORM\Column(type="integer", name="tournament")
     */
    private $tournament_id;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="tournament", referencedColumnName="id")
     */
    private $tournament;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="integer", name="referee")
     */
    private $referee_id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="referee", referencedColumnName="id")
     */
    private $referee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set teamId
     *
     * @param integer $teamId
     *
     * @return Performance
     */
    public function setTeamId($teamId)
    {
        $this->team_id = $teamId;

        return $this;
    }

    /**
     * Get teamId
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->team_id;
    }

    /**
     * Set sportId
     *
     * @param integer $sportId
     *
     * @return Performance
     */
    public function setSportId($sportId)
    {
        $this->sport_id = $sportId;

        return $this;
    }

    /**
     * Get sportId
     *
     * @return integer
     */
    public function getSportId()
    {
        return $this->sport_id;
    }

    /**
     * Set tournamentId
     *
     * @param integer $tournamentId
     *
     * @return Performance
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournament_id = $tournamentId;

        return $this;
    }

    /**
     * Get tournamentId
     *
     * @return integer
     */
    public function getTournamentId()
    {
        return $this->tournament_id;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Performance
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set refereeId
     *
     * @param integer $refereeId
     *
     * @return Performance
     */
    public function setRefereeId($refereeId)
    {
        $this->referee_id = $refereeId;

        return $this;
    }

    /**
     * Get refereeId
     *
     * @return integer
     */
    public function getRefereeId()
    {
        return $this->referee_id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Performance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set team
     *
     * @param \AppBundle\Entity\Team $team
     *
     * @return Performance
     */
    public function setTeam(\AppBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \AppBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set sport
     *
     * @param \AppBundle\Entity\Sport $sport
     *
     * @return Performance
     */
    public function setSport(\AppBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \AppBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Set tournament
     *
     * @param \AppBundle\Entity\Tournament $tournament
     *
     * @return Performance
     */
    public function setTournament(\AppBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \AppBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set referee
     *
     * @param \AppBundle\Entity\User $referee
     *
     * @return Performance
     */
    public function setReferee(\AppBundle\Entity\User $referee = null)
    {
        $this->referee = $referee;

        return $this;
    }

    /**
     * Get referee
     *
     * @return \AppBundle\Entity\User
     */
    public function getReferee()
    {
        return $this->referee;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'id' => $this->id,
            'team' => $this->team,
            'sport' => $this->sport,
            'points' => $this->points,
            'date' => $this->date,
            //'referee' => $this->referee
        );
    }
}
