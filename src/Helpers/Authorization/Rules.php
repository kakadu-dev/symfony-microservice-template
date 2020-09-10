<?php

namespace App\Helpers\Authorization;

/**
 * Class Rules
 * @package App\Helpers\Authorization
 */
class Rules
{
    private const SERVICE_NAME = 'template';

    /**
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Get auth rules
     *
     * @return array
     */
    static public function rules(): array
    {
        return [
            'attributes' => [],

            'filters' => [],

            'methods' => [],

            'permissions' => [],
        ];
    }
}
