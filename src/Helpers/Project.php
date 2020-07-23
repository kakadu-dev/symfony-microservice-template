<?php

namespace App\Helpers;

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
     * Project constructor.
     *
     * @param string    $projectAlias
     * @param string    $serviceName
     * @param string    $appDirName
     * @param string    $appEnv
     * @param string    $appDebug
     * @param bool|null $ijsonHost
     */
    public function __construct(
        string $projectAlias,
        string $serviceName,
        string $appDirName,
        string $appEnv = 'dev',
        string $appDebug = '1',
        ?bool $ijsonHost = null
    ) {
        $this->appEnv       = $appEnv;
        $this->appDebug     = (bool) $appDebug;
        $this->projectAlias = $projectAlias;
        $this->serviceName  = $serviceName;
        $this->appDirName   = $appDirName;
        $this->ijsonHost    = $ijsonHost;
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
}
