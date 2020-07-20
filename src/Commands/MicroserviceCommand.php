<?php

namespace App\Commands;

use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class MicroserviceCommand extends Command
{
    const YII_ENV = 'example';

    /**
     * @var string
     */
    protected static $defaultName = 'microservice:start';

    protected function configure()
    {
        // TODO get configure from microservice()

        $this
            ->setDescription('Create microservice');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO Get environment from config
        $projectAlias = 'panel';
        $serviceName = 'base';
        $ijsonHost = null;

        // TODO logger
        Microservice::create("$projectAlias:$serviceName", [
            'ijson' => $ijsonHost,
            'env'   => self::YII_ENV,
        ]);

        // TODO add controller
        Microservice::getInstance()->start(function ($method, $params) {
            $route = str_replace('.', '/', $method);
//            return Yii::$app->runAction($route, $params);
        });
    }
}
