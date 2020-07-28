<?php

namespace App\Commands;

use App\Helpers\{Methods\PanelMethod, MicroserviceHelper, Project};
use Exception;
use Kakadu\Microservices\exceptions\MicroserviceException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
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
     * Configure constructor.
     *
     * @param MicroserviceHelper $microservice
     * @param Project            $project
     */
    public function __construct(MicroserviceHelper $microservice, Project $project)
    {
        $this->microservice = $microservice;
        $this->project      = Project::setInstance($project);

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
                $this->putEnv(self::ENV_DEV);
                $output->writeln(['', 'Environments were set for Development project.', '']);
            break;
            case '1':
                $this->putEnv(self::ENV_PROD);
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
    public function putEnv(string $appEnv): void
    {
        $appDebug  = (string) $appEnv === self::ENV_DEV ? 1 : 0;
        $appSecret = md5(time());

        file_put_contents(
            $this->getPathEnv(),
            "APP_ENV={$appEnv}"
            . "\n"
            . "APP_DEBUG={$appDebug}"
            . "\n"
            . "APP_SECRET={$appSecret}"
            . "\n"
            . "PANEL_ALIAS=panel"
        );
    }

    /**
     * @return string
     */
    public function getPathEnv(): string
    {
        return $this->project->getAppDirName()
            . DIRECTORY_SEPARATOR
            . '.env';
    }

    /**
     * @param array $data
     */
    public function putConfig(array $data = []): void
    {
        if (!file_exists($this->getPathConfig())) {
            $this->makeFile();
        }

        $result = Yaml::dump($this->getConfig($data),2,4,Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        file_put_contents($this->getPathConfig(), $result);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getConfig(array $data): array
    {
        return array_merge(
            [
                'parameters' => $this->getParamsConfigure($data),
            ],
            [
                'services' => [
                    '_defaults' => [
                        'autowire'      => true,
                        'autoconfigure' => true,
                    ],
//                    $this->getServiceConfigure($data),
                ],
            ],
        );
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getParamsConfigure(array $data): array
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getServiceConfigure(array $data): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPathConfig(): string
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
    public function makeFile(): void
    {
        @mkdir(dirname($this->getPathConfig()), 0777, true);
    }
}
