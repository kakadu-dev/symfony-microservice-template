<?php

namespace App\Helpers\Query\Symfony;

use App\Helpers\Query\JsonParserInterface;

/**
 * Interface SymfonyJsonParserInterface
 * @package App\Helpers\Query\Symfony
 */
interface SymfonyParserInterface extends JsonParserInterface
{
    /**
     * @return string|null
     */
    public function getCondition(): ?string;

    /**
     * @return array
     */
    public function getParameters(): array;
}
