<?php

namespace App\modules\rpc\event;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * Class Controller
 * @package App\modules\rpc\EventListener
 */
class Controller
{
    /**
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event)
    {
        // TODO something
    }
}