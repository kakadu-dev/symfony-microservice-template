<?php

namespace App\Helpers;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class MicroservicePaginator
 * @package App\Helpers
 */
class MicroservicePaginator
{
    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @var int
     */
    private int $page = 1;

    /**
     * @var int
     */
    private int $limit = 20;

    /**
     * @var bool
     */
    private bool $allPage = false;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param $page
     *
     * @return $this
     */
    public function setPage($page): self
    {
        $this->page = (int) $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param $limit
     *
     * @return $this
     */
    public function setLimit($limit): self
    {
        $this->limit = (int) $limit;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllPage(): bool
    {
        return $this->allPage;
    }

    /**
     * @param $allPage
     *
     * @return $this
     */
    public function setAllPage($allPage): self
    {
        $this->allPage = (bool) $allPage;

        return $this;
    }

    /**
     * MicroservicePaginator constructor.
     *
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function getData($data): array
    {
        if ($this->isAllPage()) {
            if ($data instanceof QueryBuilder) {
                return [
                    'list' => $data->getQuery()->getResult(),
                ];
            }

            return [
              'list' => $data,
            ];
        }

        $result = $this->paginator->paginate(
            $data,
            $this->getPage(),
            $this->getLimit()
        );

        return [
            'list'       => $result->getItems(),
            'pagination' => [
                'totalItems'  => $result->count(),
                'pageCount'   => (int) ceil($result->getTotalItemCount() / $result->getItemNumberPerPage()),
                'currentPage' => $result->getCurrentPageNumber(),
                'perPage'     => $result->getItemNumberPerPage(),
            ],
        ];
    }
}
