<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DemoController
 * @package App\Controller
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
