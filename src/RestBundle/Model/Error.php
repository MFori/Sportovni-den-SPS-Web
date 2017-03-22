<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 29.12.2016
 * Time: 20:15
 */

namespace RestBundle\Model;

/**
 * Class Error
 * @package RestBundle\Model
 */
class Error implements RestSerializable
{
    // Url does not exist (or combination with url and http request method)
    const BAD_URL = 1;
    // The requested resource does not exist
    const NO_RESOURCE = 2;
    // Bad data in request or empty
    const BAD_REQUEST = 3;
    // Authorization required
    const AUTHORIZATION = 4;
    // Requested data are out of date
    const OLD_DATA = 5;

    const TODO_CLEAR_CACHE = 1;
    const TODO_LOGOUT = 2;
    const TODO_SHOW = 3;

    /**
     * Code
     * @var integer
     */
    private $code;
    /**
     * Message
     * @var string
     */
    private $message;
    /**
     * TODO
     * @var array
     */
    private $todo;

    /**
     * Default construct
     * @param int $code
     * @param string $message
     * @param array $todo
     */
    public function __construct($code, $message = '', $todo = array())
    {
        $this->code = $code;
        $this->message = $message;
        $this->todo = $todo;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get to_do
     *
     * @return array
     */
    public function getTodo()
    {
        return $this->todo;
    }

    /**
     * Set to_do
     * @param array $todos
     */
    public function setTodo($todos)
    {
        $this->todo = $todos;
    }

    /**
     * Add to to_do
     *
     * @param $todo integer
     */
    public function addTodo($todo)
    {
        $this->todo[] = $todo;
    }

    /**
     * Serialize object for rest api
     * @return array of data for json serializing
     */
    public function restSerialize()
    {
        return array(
            'code' => $this->code,
            'message' => $this->message,
            'todo' => sizeof($this->todo) > 0 ? $this->todo : null
        );
    }
}