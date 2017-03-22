<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 29.12.2016
 * Time: 17:25
 */

namespace RestBundle\Model;

/**
 * Interface RestSerializable
 * @package RestBundle\Model
 */
interface RestSerializable
{
    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize();
}