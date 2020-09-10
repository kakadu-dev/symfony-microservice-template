<?php

namespace App\Events;

use Kakadu\Microservices\exceptions\MicroserviceException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class Exception
 * @package App\Events
 */
class Exception
{
    /**
     * @param ExceptionEvent $event
     *
     * @throws MicroserviceException
     */
    public function onKernelException(ExceptionEvent $event)
    {
        throw new MicroserviceException($event->getThrowable()->getMessage());
    }
}
