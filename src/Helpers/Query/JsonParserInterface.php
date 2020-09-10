<?php

namespace App\Helpers\Query;

/**
 * Interface JsonParserInterface
 * @package App\Helpers\Query
 */
interface JsonParserInterface
{
    /**
     * @param array $condition
     *
     */
    public function parseJson(array $condition);

    /**
     * @param string $modelName
     *
     * @return $this
     */
    public function setModelName(string $modelName): self;
}