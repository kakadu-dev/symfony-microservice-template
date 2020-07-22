<?php

namespace App\modules\rpc\event;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * Class View
 * @package App\modules\rpc\EventListener
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

        $response = new JsonResponse($value);

        $event->setResponse($response);
    }
}