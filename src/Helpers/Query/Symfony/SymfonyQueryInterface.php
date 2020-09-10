<?php

namespace App\Helpers\Query\Symfony;

/**
 * Interface SymfonyQueryInterface
 * @package App\Helpers\Query\Symfony
 */
interface SymfonyQueryInterface
{
    /**
     * @return array
     */
    public function getParameters(): array;
}
