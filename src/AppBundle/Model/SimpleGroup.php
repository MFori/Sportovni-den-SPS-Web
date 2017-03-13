<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 27.01.2017
 * Time: 18:29
 */

namespace AppBundle\Model;

use RestBundle\Model\RestSerializable;

class SimpleGroup implements RestSerializable{
    protected $name;
    protected $group;
    private $core;

    public function __construct($group, $name){
        $this->group = $group;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * @param mixed $core
     */
    public function setCore($core)
    {
        $this->core = $core;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'group' => $this->group,
            'name' => $this->name
        );
    }


}