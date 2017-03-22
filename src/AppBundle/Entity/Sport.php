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
 * Class Sport
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="sport")
 */
class Sport implements RestSerializable
{
    /**
     * Id of sport
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Name of sport
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Is sport active?
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * Sports rules
     * @ORM\OneToOne(targetEntity="Rules", mappedBy="sport")
     */
    private $rules;

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @return Sport
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Sport
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set rules
     *
     * @param \AppBundle\Entity\Rules $rules
     *
     * @return Sport
     */
    public function setRules(\AppBundle\Entity\Rules $rules = null)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Get rules
     *
     * @return \AppBundle\Entity\Rules
     */
    public function getRules()
    {
        return $this->rules;
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
            'active' => $this->active,
        );
    }
}
