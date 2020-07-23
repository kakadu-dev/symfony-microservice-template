<?php

namespace App\Helpers;

use Exception;
use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * Class Microservice
 * @package App\Helpers
 */
class MicroserviceHelper
{
    /**
     * @var Project
     */
    public Project $project;

    /**
     * MicroserviceHelper constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param ConsoleLogger $logger
     *
     * @throws Exception
     */
    public function init(ConsoleLogger $logger)
    {
        Microservice::create("{$this->project->getProjectAlias()}:{$this->project->getServiceName()}", [
            'ijson' => $this->project->getIjsonHost(),
            'env'   => $this->project->getAppEnv(),
        ], $logger);;
    }
}
