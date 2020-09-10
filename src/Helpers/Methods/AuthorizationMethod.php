<?php

namespace App\Helpers\Methods;

use Kakadu\Microservices\exceptions\MicroserviceException;
use Kakadu\Microservices\{
    Microservice,
    MjResponse
};

/**
 * Class AuthorizationMethod
 * @package App\Helpers\Methods
 */
class AuthorizationMethod extends AbstractMethod
{
    const IMPORT_SERVICE_RULE    = 'actions.import-service-rules';

    /**
     * @var string
     */
    public static string $serviceName = 'authorization';

    /**
     * Import microservice authorization rules
     *
     * @param string $service
     * @param string $version
     * @param array  $rules
     *
     * @return MjResponse|null
     * @throws MicroserviceException
     */
    public static function importRules(string $service, string $version, array $rules): ?MjResponse
    {
        return Microservice::getInstance()
            ->sendServiceRequest(
                self::getServiceMethod(),
                self::IMPORT_SERVICE_RULE,
                [
                    'service' => $service,
                    'version' => $version,
                    'rules'   => $rules,
                ],
                true,
                [
                    'headers' => [
                        'Option' => 'if present',
                    ],
                ]
            );
    }
}
