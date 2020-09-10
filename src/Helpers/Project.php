<?php

namespace App\Helpers;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class Project
 * @package App\Helpers
 */
class Project
{
    /**
     * @var string
     */
    private string $appEnv;

    /**
     * @var bool
     */
    private bool $appDebug;

    /**
     * @var string
     */
    private string $projectAlias;

    /**
     * @var string
     */
    private string $serviceName;

    /**
     * @var string|null
     */
    private ?string $ijsonHost;

    /**
     * @var string
     */
    private string $appDirName;

    /**
     * @var bool
     */
    private bool $isDisabledControlPanel;

    /**
     * @var bool
     */
    private bool $isDisabledAuthorization;

    /**
     * @var Project|null
     */
    private static ?Project $instance = null;

    /**
     * Project constructor.
     *
     * @param ContainerBagInterface $bag
     * @param ENV                   $env
     */
    public function __construct(ContainerBagInterface $bag, ENV $env)
    {
        $this->appDirName   = $bag->get('kernel.project_dir');
        $this->serviceName  = $bag->get('microservice.service_name');
        $this->appDebug     = (bool) $env->get('APP_DEBUG', false);
        $this->appEnv       = (string) $env->get('APP_ENV', 'dev');
        $this->projectAlias = (string) $env->get('PROJECT_ALIAS', 'panel');
        $this->ijsonHost    = $env->get('IJSON_HOST', null);
        $this->isDisabledControlPanel = $env->get('CONTROL_PANEL_DISABLE', 'no') === 'yes';
        $this->isDisabledAuthorization = $env->get('AUTHORIZATION_DISABLE', 'yes') === 'yes';
    }

    /**
     * @return string
     */
    public function getAppEnv(): string
    {
        return $this->appEnv;
    }

    /**
     * @return bool
     */
    public function getAppDebug(): bool
    {
        return $this->appDebug;
    }

    /**
     * @return string
     */
    public function getProjectAlias(): string
    {
        return $this->projectAlias;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return string|null
     */
    public function getIjsonHost(): ?string
    {
        return $this->ijsonHost;
    }

    /**
     * @return string
     */
    public function getAppDirName(): string
    {
        return $this->appDirName;
    }

    /**
     * @return bool
     */
    public function isDisabledControlPanel(): bool
    {
        return $this->isDisabledControlPanel;
    }

    /**
     * @return bool
     */
    public function isDisabledAuthorization(): bool
    {
        return $this->isDisabledAuthorization;
    }

    /**
     * @param Project|null $project
     *
     * @return Project
     */
    public static function setInstance(Project $project): Project
    {
        if (self::$instance === null) {
            self::$instance = $project;
        }

        return self::$instance;
    }

    /**
     * @return Project|null
     */
    public static function getInstance(): ?Project
    {
        return self::$instance;
    }
}
