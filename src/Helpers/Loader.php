<?php

namespace App\Helpers;

use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class Loader
 * @package App\Helpers
 */
class Loader
{
    /**
     * @param object                      $model
     * @param array                       $data
     *
     * @return object
     */
    public static function load(object $model, array $data): object
    {
        $accessor = new PropertyAccessor();

        foreach ($data as $property => $value) {

            if (!$accessor->isWritable($model, $property)) {
                continue;
            }

            $accessor->setValue($model, $property, $value);
        }

        return $model;
    }

    /**
     * @param object $model
     * @param string $property
     *
     * @return bool
     */
    private static function hasSetter(object $model, string $property): bool
    {
        return method_exists($model, self::getSetter($property));
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private static function getSetter(string $property): string
    {
        return 'set' . ucfirst($property);
    }
}
