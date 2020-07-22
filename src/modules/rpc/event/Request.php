<?php

namespace App\modules\rpc\event;

use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class Controller
 * @package App\modules\rpc\EventListener
 */
class Request
{
    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        // TODO something
    }
}