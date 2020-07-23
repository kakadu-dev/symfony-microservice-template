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
     * Project constructor.
     *
     * @param string    $projectAlias
     * @param string    $serviceName
     * @param bool|null $ijsonHost
     */
    public function __construct(string $projectAlias, string $serviceName, ?bool $ijsonHost = null)
    {
        $this->appEnv       = $_SERVER['APP_ENV'];
        $this->appDebug     = (bool) $_SERVER['APP_DEBUG'];
        $this->projectAlias = $projectAlias;
        $this->serviceName  = $serviceName;
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
}
