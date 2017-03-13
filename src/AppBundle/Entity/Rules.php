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
 * Class Rules
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="rules")
 */
class Rules implements RestSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="sport")
     */
    private $sport_id;

    /**
     * @ORM\OneToOne(targetEntity="Sport", inversedBy="rules")
     * @ORM\JoinColumn(name="sport", referencedColumnName="id")
     */
    private $sport;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime", name="edit_date")
     */
    private $editDate;

    /**
     * @ORM\Column(type="integer", name="edit_user")
     */
    private $editUser_id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="edit_user", referencedColumnName="id")
     */
    private $editUser;

    /**
     * Set sportId
     *
     * @param integer $sportId
     *
     * @return Rules
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
     * Set text
     *
     * @param string $text
     *
     * @return Rules
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set editDate
     *
     * @param \DateTime $editDate
     *
     * @return Rules
     */
    public function setEditDate($editDate)
    {
        $this->editDate = $editDate;

        return $this;
    }

    /**
     * Get editDate
     *
     * @return \DateTime
     */
    public function getEditDate()
    {
        return $this->editDate;
    }

    /**
     * Set editUserId
     *
     * @param \DateTime $editUserId
     *
     * @return Rules
     */
    public function setEditUserId($editUserId)
    {
        $this->editUser_id = $editUserId;

        return $this;
    }

    /**
     * Get editUserId
     *
     * @return \DateTime
     */
    public function getEditUserId()
    {
        return $this->editUser_id;
    }

    /**
     * Set sport
     *
     * @param \AppBundle\Entity\Sport $sport
     *
     * @return Rules
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
     * Set editUser
     *
     * @param \AppBundle\Entity\User $editUser
     *
     * @return Rules
     */
    public function setEditUser(\AppBundle\Entity\User $editUser = null)
    {
        $this->editUser = $editUser;

        return $this;
    }

    /**
     * Get editUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getEditUser()
    {
        return $this->editUser;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'sport' => $this->sport,
            'text' => $this->text
        );
    }
}
