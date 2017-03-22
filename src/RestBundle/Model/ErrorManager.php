<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 29.12.2016
 * Time: 20:07
 */

namespace RestBundle\Model;

/**
 * Class ErrorManager
 * @package RestBundle\Model
 */
class ErrorManager
{
    /**
     * Private construct
     */
    private function __construct()
    {
    }

    /**
     * Get error
     * @param int $error
     * @param null $message
     * @param array $todo
     * @return Error
     */
    public static function getError($error, $message = null, $todo = array())
    {
        if ($message == null && isset(self::$ErrorMessages[$error]))
            $message = self::$ErrorMessages[$error];

        foreach($todo as $k => $todo_code) {
            $todo[$k] = array(
                'code' => $todo_code,
                'message' => self::$TodoMessages[$todo_code]
            );
        }

        return new Error($error, $message, $todo);
    }

    /**
     * List of error messages
     * @var array
     */
    private static $ErrorMessages = array(
        Error::BAD_URL       => 'Requested url or combination with request method does not exist.',
        Error::NO_RESOURCE   => 'The requested resource does not exist.',
        Error::BAD_REQUEST   => 'There were errors in structure of request.',
        Error::AUTHORIZATION => 'Authorization header required for this url.',
        Error::OLD_DATA      => 'Requested data are out of date.',
    );

    /**
     * List of todo messages
     * @var array
     */
    private static $TodoMessages = array(
        Error::TODO_CLEAR_CACHE => 'Clear the cache.',
        Error::TODO_LOGOUT      => 'Logout user.',
        Error::TODO_SHOW        => 'Show error message.',
    );
}