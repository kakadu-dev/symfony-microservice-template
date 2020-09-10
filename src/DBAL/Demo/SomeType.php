<?php

namespace App\DBAL\Demo;

use App\DBAL\EnumType;

/**
 * Class CityType
 * @package App\DBAL\Demo
 */
class SomeType extends EnumType
{
    public const FIRST  = 'first';
    public const SECOND = 'second';

    protected string $name = self::class;

    protected array $values = [
        self::FIRST  => 'первый атрибут',
        self::SECOND => 'второй атрибут',
    ];
}
