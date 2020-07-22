<?php

namespace App\Commands;

use App\Kernel;
use Exception;
use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class MicroserviceCommand extends Command
{
    /**
     * @var string
     */
    public string $appEnv;

    /**
     * @var bool
     */
    public bool $appDebug;

    /**
     * @var string
     */
    public string $projectAlias;

    /**
     * @var string
     */
    public string $serviceName;

    /**
     * @var string|null
     */
    public ?string $ijsonHost;

    /**
     * @var string
     */
    protected static $defaultName = 'microservice:start';

    /**
     * MicroserviceCommand constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->appEnv = $_SERVER['APP_ENV'];
        $this->appDebug = (bool) $_SERVER['APP_DEBUG'];
        $this->projectAlias = $params['projectAlias'];
        $this->serviceName = $params['serviceName'];
        $this->ijsonHost = $params['ijsonHost'];

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Get running microservice');
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
        $this->createMicroservice($output);

        Microservice::getInstance()->start(function ($method, $params) {
            $route = str_replace('.', '/', $method);

            $kernel  = new Kernel($this->appEnv, $this->appDebug);
            $request = Request::create($route, 'POST', $params);

            return $kernel->handle($request)->getContent();
        });
    }

    /**
     * @param OutputInterface $output
     *
     * @throws Exception
     */
    public function createMicroservice(OutputInterface $output): void
    {
        $logger = new ConsoleLogger($output);

        Microservice::create("{$this->projectAlias}:{$this->serviceName}", [
            'ijson' => $this->ijsonHost,
            'env'   => $this->appEnv,
        ], $logger);
    }
}
