<?php

namespace App\Helpers\Methods;

use App\Helpers\Project;

/**
 * Class AbstractMethod
 * @package App\Helpers\Methods
 */
class AbstractMethod
{
    /**
     * @var string
     */
    public static string $serviceName = '';

    /**
     * Get service method name
     *
     * @return string
     */
    protected static function getServiceMethod(): string
    {
        return self::getProjectAlias() . ':' . static::$serviceName;
    }

    /**
     * Get project alias
     *
     * @return string
     */
    protected static function getProjectAlias(): string
    {
        return Project::getInstance()->getProjectAlias();
    }
}
