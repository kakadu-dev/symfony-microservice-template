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
     * @var string
     */
    private string $panelAlias;

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
        $this->appDebug     = (bool) $env->get('APP_DEBUG', false);
        $this->appEnv       = (string) $env->get('APP_ENV', 'dev');
        $this->panelAlias   = (string) $env->get('PANEL_ALIAS', 'panel');
        $this->projectAlias = $bag->has('microservice.project_alias')
            ? $bag->get('microservice.project_alias')
            : 'panel';
        $this->serviceName  = $bag->has('microservice.service_name')
            ? $bag->get('microservice.service_name')
            : 'base';
        $this->ijsonHost    = $bag->has('microservice.ijson_host')
            ? $bag->get('microservice.ijson_host')
            : null;
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
     * @return string
     */
    public function getPanelAlias(): string
    {
        return $this->panelAlias;
    }

    /**
     * @return Project|null
     */
    public static function getInstance(): ?Project
    {
        return self::$instance;
    }
}
