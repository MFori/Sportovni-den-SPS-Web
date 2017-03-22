<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 22:44
 */

namespace RestBundle\Model\Exception;

use RestBundle\Model\Error;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnauthorizedException
 * @package RestBundle\Model\Exception
 */
class UnauthorizedException extends RestException
{
    /**
     * Get http code of response
     * @return int
     */
    protected function getHttpStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    /**
     * Get data about exception
     * @return array
     */
    protected function getExceptionData()
    {
        return array();
    }

    /**
     * Get array of errors to add to Rest response
     * @return array
     */
    protected function getErrors()
    {
        return array(
            $this->createError(Error::AUTHORIZATION)
        );
    }
}