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
 * Match status represent start of match without any result
 * @var string
 */
define('STATUS_CREATED', 'STATUS_CREATED');

/**
 * Match status represent end of match
 * @var string
 */
define('STATUS_END', 'STATUS_END');

/**
 * Class MatchStatus
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="match_status")
 */
class MatchStatus implements RestSerializable
{
    /**
     * Id of status
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Name of status
     * @ORM\Column(type="string")
     */
    private $name;

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
     * @return MatchStatus
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
     * Has status
     * @param $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return in_array($status, explode(';', $this->name));
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name
        );
    }
}
