<?php

namespace App\Helpers\Query;

/**
 * Interface QueryInterface
 * @package App\Helpers\Query
 */
interface QueryInterface
{
    /**
     * @return bool
     */
    public function getAllPage(): bool;

    /**
     * @return array|string[]
     */
    public function getAttributes(): array;

    /**
     * @return mixed
     */
    public function getWhere();

    /**
     * @return array
     */
    public function getWith(): array;

    /**
     * @return array
     */
    public function getOrderBy(): array;

    /**
     * @return int|null
     */
    public function getPerPage(): ?int;

    /**
     * @return int|null
     */
    public function getPage(): ?int;
}
