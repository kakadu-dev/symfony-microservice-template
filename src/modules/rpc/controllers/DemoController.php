<?php

namespace App\modules\rpc\controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DemoController
 * @package App\modules\rpc\controllers
 */
class DemoController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function hello(Request $request): array
    {
        return $request->request->all();
    }
}
