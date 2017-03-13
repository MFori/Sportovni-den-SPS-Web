<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 19:16
 */

namespace RestBundle\Model\Exception;

use RestBundle\Model\RestResponse;
use RestBundle\Model\ErrorManager;

abstract class RestException extends \Exception
{
    /**
     * @var array
     */
    protected $data;

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    protected abstract function getHttpStatusCode();

    protected abstract function getExceptionData();

    protected abstract function getErrors();

    /**
     * @param $errorCode
     * @param null $message
     * @param array $todo
     * @return \RestBundle\Model\Error
     */
    protected function createError($errorCode, $message = null, $todo = array())
    {
        return ErrorManager::getError($errorCode, $message, $todo);
    }

    /**
     * @param $data array
     */
    public function addData($data)
    {
        $this->data = $data;
    }

    /**
     * @return RestResponse
     */
    public function getResponse()
    {
        $response = new RestResponse(
            array_merge($this->data, $this->getExceptionData()),
            $this->getHttpStatusCode(),
            $this->getErrors()
        );

        return $response;
    }
}