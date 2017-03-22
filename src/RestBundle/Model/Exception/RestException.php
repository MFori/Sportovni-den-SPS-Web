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

/**
 * Class RestException
 * @package RestBundle\Model\Exception
 */
abstract class RestException extends \Exception
{
    /**
     * Data for response
     * @var array
     */
    protected $data;

    /**
     * Public construct
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * Get http code of response
     * @return int
     */
    protected abstract function getHttpStatusCode();

    /**
     * Get data about exception
     * @return array
     */
    protected abstract function getExceptionData();

    /**
     * Get array of errors to add to Rest response
     * @return array
     */
    protected abstract function getErrors();

    /**
     * Create error instance
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
     * Add data
     *
     * @param $data array
     */
    public function addData($data)
    {
        $this->data = $data;
    }

    /**
     * Get json response for Rest
     *
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