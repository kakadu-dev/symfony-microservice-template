<?php

namespace App\Commands;

use App\Helpers\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

/**
 * Class SeederCommand
 * @package App\Commands
 */
class SeederCommand extends Command
{
    private const LOCAL_FOLDER_PATH = 'migrations/microservices';

    /**
     * @var Project
     */
    public Project $project;

    /**
     * SeederCommand constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = Project::setInstance($project);
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('microservice:seed')
            ->setDescription('Seeding data into DB');
    }

    /**
     * @return array
     */
    private function getAllSqlPath(): array
    {
        $path = $this->project->getAppDirName()
            . DIRECTORY_SEPARATOR
            . self::LOCAL_FOLDER_PATH
            . DIRECTORY_SEPARATOR;

        return glob($path . '*.sql');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['']);

        foreach ($this->getPaths() as $path) {
            $command = $this->getApplication()->find('doctrine:database:import');

            $arguments = [
                'file' => $path
            ];

            $importInput = new ArrayInput($arguments);
            $command->run($importInput, $output);
        }

        $output->writeln([
            'seeding completed!',
            ''
        ]);

        return Command::SUCCESS;
    }

    /**
     * there are we sort slq file with relation order
     *
     * @return array
     */
    private function getPaths(): array
    {
        $filesPath = [];
        foreach ($this->getAllSqlPath() as $path) {
            if (strpos($path, 'cities.sql') !== false) {
                $filesPath[1] = $path;
            }

            if (strpos($path, 'countries.sql') !== false) {
                $filesPath[0] = $path;
            }
        }

        ksort($filesPath);

        return $filesPath;
    }
}
