<?php

namespace App\Components;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class MicroserviceRequest
 * @package App\Components
 */
class MicroserviceRequest extends Request
{
    /**
     * @var array
     */
    protected static array $microserviceQuery;

    /**
     * @inheritDoc
     */
    public static function create(string $uri, string $method = 'GET', array $parameters = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        if (!empty($parameters) && array_key_exists('query', $parameters)) {
            self::$microserviceQuery = $parameters['query'];
        }

        return parent::create($uri, $method, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * @return array
     */
    public static function getQuery(): array
    {
        return self::$microserviceQuery ?? [];
    }

    /**
     * @return array
     */
    public static function getExpands()
    {
        return self::getQuery()['expands'] ?? [];
    }
}
