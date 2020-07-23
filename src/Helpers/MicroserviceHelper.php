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
        Microservice::create("{$this->project->projectAlias}:{$this->project->serviceName}", [
            'ijson' => $this->project->ijsonHost,
            'env'   => $this->project->appEnv,
        ], $logger);;
    }
}
