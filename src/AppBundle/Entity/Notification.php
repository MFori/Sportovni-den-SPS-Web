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
 * Class Notification
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification implements RestSerializable
{
    /**
     * Id of notification
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Notifications title
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * Notifications text - body
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * Id of notifications addressee
     * @ORM\Column(type="integer", name="addressee")
     */
    private $addressee_id;

    /**
     * Addressee object
     * @ORM\ManyToOne(targetEntity="Addressee")
     * @ORM\JoinColumn(name="addressee", referencedColumnName="id")
     */
    private $addressee;

    /**
     * Id of notifications sender
     * @ORM\Column(type="integer", name="sender")
     */
    private $sender_id;

    /**
     * Sender object
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sender", referencedColumnName="id")
     */
    private $sender;

    /**
     * Date of sending notification
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
     * Set title
     *
     * @param string $title
     *
     * @return Notification
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Notification
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
     * Set addresseeId
     *
     * @param integer $addresseeId
     *
     * @return Notification
     */
    public function setAddresseeId($addresseeId)
    {
        $this->addressee_id = $addresseeId;

        return $this;
    }

    /**
     * Get addresseeId
     *
     * @return integer
     */
    public function getAddresseeId()
    {
        return $this->addressee_id;
    }

    /**
     * Set senderId
     *
     * @param integer $senderId
     *
     * @return Notification
     */
    public function setSenderId($senderId)
    {
        $this->sender_id = $senderId;

        return $this;
    }

    /**
     * Get senderId
     *
     * @return integer
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Notification
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
     * Set addressee
     *
     * @param \AppBundle\Entity\Addressee $addressee
     *
     * @return Notification
     */
    public function setAddressee(\AppBundle\Entity\Addressee $addressee = null)
    {
        $this->addressee = $addressee;

        return $this;
    }

    /**
     * Get addressee
     *
     * @return \AppBundle\Entity\Addressee
     */
    public function getAddressee()
    {
        return $this->addressee;
    }

    /**
     * Set sender
     *
     * @param \AppBundle\Entity\User $sender
     *
     * @return Notification
     */
    public function setSender(\AppBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \AppBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'sender' => $this->sender->getId(),
            'date' => $this->date
        );
    }
}
