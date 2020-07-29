<?php

namespace App\Commands;

use App\Helpers\{ENV, Methods\PanelMethod, MicroserviceHelper, Project};
use Exception;
use Kakadu\Microservices\exceptions\MicroserviceException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PropertyAccess\{PropertyAccess, PropertyAccessor};
use Symfony\Component\Yaml\Yaml;

/**
 * Class Configure
 * @package App\Commands
 */
class Configure extends Command
{
    private const ENV_DEV  = 'dev';
    private const ENV_PROD = 'prod';

    /**
     * @var MicroserviceHelper
     */
    public MicroserviceHelper $microservice;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * @var PropertyAccessor
     */
    public PropertyAccessor $propertyAccessor;

    /**
     * @var ENV
     */
    public ENV $env;

    /**
     * Configure constructor.
     *
     * @param MicroserviceHelper $microservice
     * @param Project            $project
     * @param ENV                $env
     */
    public function __construct(MicroserviceHelper $microservice, Project $project, ENV $env)
    {
        $this->microservice     = $microservice;
        $this->project          = Project::setInstance($project);
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->env              = $env;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('microservice:configure')
            ->setDescription('Configure project');
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
        $helper = $this->getHelper('question');

        $output->writeln([
            '',
            'Which environment do you want the application to be initialized in?',
            '',
            '   [0] Development',
            '   [1] Production',
            '',
        ]);

        $questionAppEnv = new Question('Your choice [0-1, or "q" to quit]: ', 'dev');
        $answerAppEnv   = $helper->ask($input, $output, $questionAppEnv);

        switch ($answerAppEnv) {
            case '0':
                $this->putDefaultEnv(self::ENV_DEV);
                $output->writeln(['', 'Environments were set for Development project.', '']);
            break;
            case '1':
                $this->putDefaultEnv(self::ENV_PROD);
                $output->writeln(['', 'Environments were set for Production project.', '']);
            break;
            case 'q':
                $output->writeln('Bye!');

                return Command::SUCCESS;
            default:
                $output->writeln('Something went wrong!');

                return Command::FAILURE;
        }

        $this->microservice->init(new ConsoleLogger($output));

        $this->putConfig($this->getProject());

        $output->writeln(['', 'Configuration project were installed successfully!']);

        return Command::SUCCESS;
    }

    /**
     * @return mixed
     * @throws MicroserviceException|Exception
     */
    public function getProject()
    {
        $projectAlias = $this->project->getProjectAlias();
        $serviceName  = $this->project->getServiceName();

        $result = PanelMethod::viewProject([
            'alias' => $projectAlias,
            'query' => [
                'expands' => [
                    [
                        'name'  => 'MysqlCredentials',
                        'where' => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order' => ['-service'],
                        'limit' => 1,
                    ],
                    [
                        'name'     => 'RedisCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'MailCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'AwsCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'ServiceConfig',
                        'required' => false,
                        'where'    => [
                            'service' => $serviceName,
                        ],
                    ],
                    [
                        'name'     => 'FirebaseConfig',
                        'required' => false,
                    ],
                ],
            ],
        ]);

        if (!$result || empty($result->getResult()['model'] ?? null)) {
            throw new Exception("Project '$projectAlias' not found.");
        }

        return $result->getResult()['model'];
    }

    /**
     * @param string $appEnv
     */
    public function putDefaultEnv(string $appEnv): void
    {
        $this->env->put('APP_ENV', $appEnv);
        $this->env->put('APP_DEBUG', ((string) $appEnv === self::ENV_DEV ? 1 : 0));
        $this->env->put('APP_SECRET', md5(time()));
        $this->env->put('PANEL_ALIAS', 'panel');
    }

    /**
     * @param array $data
     */
    public function putConfig(array $data = []): void
    {
        if (!file_exists($this->getPathConfig())) {
            $this->makeFile();
        }

        $result = Yaml::dump(
            array_merge(
                $this->getParamsConfigure($data),
                $this->getServiceConfigure($data)
            ),
            2,
            4,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
        );

        file_put_contents($this->getPathConfig(), $result);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getParamsConfigure(array $data): array
    {
        $params = [];

        $params['microservice.project_alias'] = $data['alias'] ?? 'default';

        // TODO put params config which you want

        return [
            'parameters' => $params,
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getServiceConfigure(array $data): array
    {
        $services = [
            '_defaults' => [
                'autowire'      => true,
                'autoconfigure' => true,
            ],
        ];

        if ($mysql = $this->getRemoteConfig($data, '[MysqlCredentials][0]', '[projectId]')) {
            $this->env->put(
                'DATABASE_URL',
                'mysql://'
                . $mysql['user']
                . ':'
                . ($mysql['password'] ? : null)
                . '@'
                . $mysql['host']
                . ':'
                . $mysql['port']
                . '/'
                . ($mysql['database'] ?? $this->project->getServiceName())
                . '?'
                . 'serverVersion=5.7'
            );
        }

        // TODO put service config which you want

        return [
            'services' => $services,
        ];
    }

    /**
     * Get project remote config
     *
     * @param array  $project
     * @param string $location   "test.location.in.array"
     * @param string $checkField "field"
     *
     * @return array|null
     */
    private function getRemoteConfig(array $project, string $location, string $checkField): ?array
    {
        $config = $this->propertyAccessor->getValue($project, $location);

        if (!$config) {
            return null;
        }

        $checkValue = $this->propertyAccessor->getValue($project, $location . $checkField);

        if (!$checkValue) {
            return null;
        }

        return $config;
    }

    /**
     * @return string
     */
    private function getPathConfig(): string
    {
        return $this->project->getAppDirName()
            . DIRECTORY_SEPARATOR
            . 'config'
            . DIRECTORY_SEPARATOR
            . "services_{$this->project->getAppEnv()}.yaml";
    }

    /**
     * create configuration file
     */
    private function makeFile(): void
    {
        @mkdir(dirname($this->getPathConfig()), 0777, true);
    }
}
