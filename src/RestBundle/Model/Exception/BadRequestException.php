<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 22:53
 */

namespace RestBundle\Model\Exception;

use RestBundle\Model\Error;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends RestException
{
    protected function getHttpStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }

    protected function getExceptionData()
    {
        return array();
    }

    protected function getErrors()
    {
        return array(
            $this->createError(Error::BAD_REQUEST)
        );
    }
}