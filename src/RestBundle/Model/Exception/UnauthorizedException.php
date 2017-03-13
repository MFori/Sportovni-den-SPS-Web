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

class UnauthorizedException extends RestException
{
    protected function getHttpStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    protected function getExceptionData()
    {
        return array();
    }

    protected function getErrors()
    {
        return array(
            $this->createError(Error::AUTHORIZATION)
        );
    }
}