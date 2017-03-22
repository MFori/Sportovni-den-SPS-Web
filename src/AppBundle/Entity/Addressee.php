<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 15.12.2016
 * Time: 18:36
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RestBundle\Model\RestSerializable;

/**
 * Class Addressee
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="addressee")
 */
class Addressee implements RestSerializable
{
    const EVERYBODY = 1;
    const REFEREES = 2;
    const ATHLETES = 3;

    /**
     * Id of addressee
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Name of addressee
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * If addressee represents a team, id of the team
     * @ORM\Column(type="integer", name="team")
     */
    private $team_id;

    /**
     * If addressee represents a team, teams object
     * @ORM\OneToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    private $team;

    /**
     * Topic name for fcm
     * @ORM\Column(type="string")
     */
    private $topic;

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
     * Set name
     *
     * @param string $name
     *
     * @return Addressee
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set teamId
     *
     * @param integer $teamId
     *
     * @return Addressee
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
     * Set team
     *
     * @param \AppBundle\Entity\Team $team
     *
     * @return Addressee
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
     * Set topic
     *
     * @param string $topic
     *
     * @return Addressee
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'team' => $this->team
        );
    }
}
