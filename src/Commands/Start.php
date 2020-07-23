<?php

namespace App\Commands;

use App\Helpers\{MicroserviceHelper, Project};
use App\Kernel;
use Exception;
use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Start
 * @package App\Commands
 */
class Start extends Command
{
    /**
     * @var MicroserviceHelper
     */
    public MicroserviceHelper $microservice;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * Start constructor.
     *
     * @param MicroserviceHelper $microservice
     * @param Project            $project
     */
    public function __construct(MicroserviceHelper $microservice, Project $project)
    {
        $this->microservice = $microservice;
        $this->project      = $project;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('microservice:start')
            ->setDescription('Get running microservice');
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
        $this->microservice->init(new ConsoleLogger($output));

        Microservice::getInstance()->start(function ($method, $params) {
            $route = str_replace('.', '/', $method);

            $kernel  = new Kernel($this->project->getAppEnv(), $this->project->getAppDebug());
            $request = Request::create($route, 'POST', $params);

            return $kernel->handle($request)->getContent();
        });
    }
}
