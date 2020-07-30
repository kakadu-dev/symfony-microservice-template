<?php

namespace App\Events;

use App\Components\MicroserviceResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * Class View
 * @package App\Event
 */
class View
{
    /**
     * there is we are putting result from controller into JsonResponse($result)
     *
     * @param ViewEvent $event
     */
    public function onKernelView(ViewEvent $event): void
    {
        $value = $event->getControllerResult();

        $response = new MicroserviceResponse($value);

        $event->setResponse($response);
    }
}
