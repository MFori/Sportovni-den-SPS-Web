<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 18:40
 */

namespace RestBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use RestBundle\Model\Exception\RestException;

/**
 * Class ExceptionListener
 * @package RestBundle\Listener
 */
class ExceptionListener
{
    /**
     * On kernel exception
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof RestException) {
            $event->setResponse($exception->getResponse());
        }
    }
}