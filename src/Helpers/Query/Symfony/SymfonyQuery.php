<?php

namespace App\Helpers\Query\Symfony;

use App\Helpers\Query\{
    BaseQuery,
    JsonParserInterface,
    QueryHelper,
};
use Doctrine\ORM\{
    EntityManagerInterface,
    QueryBuilder,
};
use Doctrine\ORM\Mapping\MappingException;
use Exception;

/**
 * Class SymfonyQuery
 * @package App\Helpers\Query\Symfony
 */
class SymfonyQuery extends BaseQuery implements SymfonyQueryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $manager;

    /**
     * @var QueryBuilder
     */
    protected QueryBuilder $qb;

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @param $parameter
     *
     * @return BaseQuery
     * @throws Exception
     */
    protected function configure($parameter): BaseQuery
    {
        if (!$parameter instanceof EntityManagerInterface) {
            throw new Exception('Bad parameter, awaiting EntityManagerInterface');
        }

        $this->manager = $parameter;
        $this->qb      = $parameter->createQueryBuilder();

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     * @throws MappingException
     */
    protected function setAttributes(array $data): self
    {
        /** @var SymfonyParserInterface $parserWhere */
        $parserWhere = $this
            ->getJsonParser()
            ->setModelName($this->getMainTableName())
            ->parseJson(QueryHelper::getValue($data, 'query.filter', []));
        $this->where = $parserWhere->getCondition();
        $this->setParameter($parserWhere->getParameters());

        /** @var SymfonyParserInterface $parserWhere */
        $parserAndWhere = $this
            ->getJsonParser()
            ->setModelName($this->getMainTableName())
            ->parseJson(QueryHelper::getValue($data, 'payload.authorization.filter', []));
        $this->andWhere = $parserAndWhere->getCondition();
        $this->setParameter($parserAndWhere->getParameters());

        $this->setWith(
            QueryHelper::getValue($data, 'query.expands', []),
            $this->getMainClass()
        );

        $this->setOrderBy(
            QueryHelper::getValue($data, 'query.orderBy', []),
            $this->getMainTableName()
        );

        $this->page       = QueryHelper::getValue($data, 'query.page');
        $this->perPage    = QueryHelper::getValue($data, 'query.perPage');
        $this->allPage    = QueryHelper::getValue($data, 'query.allPage', $this->allPage);
        $this->attributes = QueryHelper::getValue($data, 'query.attributes', []);

        return $this;
    }

    /**
     * @param array $parameter
     */
    protected function setParameter(array $parameter): void
    {
        $this->parameters = array_merge(
            $this->parameters,
            $parameter
        );
    }

    /**
     * @param array  $data
     * @param string $alias
     */
    protected function setOrderBy(array $data, string $alias): void
    {
        $result = [];
        if (!empty($data)) {
            foreach ($data as $item) {

                $sort = substr($item, 0, 1) === '-' ? 'DESC' : 'ASC';

                $field = $sort === 'ASC' ? $item : substr($item, 1);

                $result["{$alias}.{$field}"] = $sort;
            }
        }

        $this->orderBy = array_merge(
            $this->orderBy ?? [],
            $result
        );
    }

    /**
     * @param array  $with
     * @param string $mainClass
     *
     * @throws MappingException
     */
    protected function setWith(array $with, string $mainClass): void
    {
        $query = [];
        foreach ($with as $item) {

            if (!is_array($item)) {
                continue;
            }

            $relation    = $item['name'];
            $expandClass = $this->getExpandClassName($mainClass, $relation);
            $expandTable = $this->getTableName($expandClass);

            if (!is_string($expandTable)) {
                throw new Exception(
                    "Class {$mainClass} doesn't have relation {$relation}}"
                );
            }

            $where = $item['where'] ?? [];
            if (!empty($where)) {
                /** @var SymfonyParserInterface $parserJoin */
                $parserJoin = $this->getJsonParser()
                    ->setModelName($relation)
                    ->parseJson($where);

                $where = $parserJoin->getCondition() ?? '';
                $this->setParameter($parserJoin->getParameters());
            }

            $orderBy = $item['order'] ?? [];
            if (!empty($orderBy)) {
                $this->setOrderBy($orderBy, $relation);
            }

            $query[] = [
                'join'          => "{$this->getTableName($mainClass)}.{$relation}",
                'alias'         => $relation,
                'conditionType' => 'WITH',
                'condition'     => $where,
            ];
        }

        $this->with = $query;
    }

    /**
     * @return JsonParserInterface
     */
    protected function getJsonParser(): JsonParserInterface
    {
        return new SymfonyParser();
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        if ($this->where && $this->andWhere) {
            return $this->where . ' and ' . $this->andWhere;
        }

        if ($this->andWhere) {
            return $this->andWhere;
        }

        return $this->where;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getWith(): array
    {
        return $this->with;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page ?? 1;
    }

    /**
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->perPage ?? 20;
    }

    /**
     * @return bool
     */
    public function getAllPage(): bool
    {
        return $this->allPage;
    }

    /**
     * @return array|string[]
     */
    public function getAttributes(): array
    {
        if (empty($this->attributes)) {
            return [$this->getMainTableName()];
        }

        return array_map(function ($attribute) {
            return "{$this->getMainTableName()}.{$attribute}";
        }, $this->attributes);
    }

    /**
     * @return string|null
     */
    protected function getMainTableName(): ?string
    {
        if ($this->getMainClass() === null) {
            return null;
        }

        return $this
            ->manager
            ->getClassMetadata($this->getMainClass())
            ->getTableName();
    }

    /**
     * @return string|null
     */
    protected function getMainClass(): ?string
    {
        return $this->mainClass;
    }

    /**
     * @param string|null $className
     *
     * @return string|null
     */
    public function getTableName(?string $className): ?string
    {
        if (!is_string($className)) {
            return null;
        }

        return $this
            ->manager
            ->getClassMetadata($className)
            ->getTableName();
    }

    /**
     * @param string $mainClass
     * @param string $expandName
     *
     * @return string|null
     * @throws MappingException
     */
    public function getExpandClassName(string $mainClass, string $expandName): ?string
    {
        $hasProperty = $this
            ->manager
            ->getMetadataFactory()
            ->getMetadataFor($mainClass)
            ->getReflectionClass()
            ->hasProperty($expandName);

        if (!$hasProperty) {
            return null;
        }

        $association = $this
            ->manager
            ->getClassMetadata($mainClass)
            ->getAssociationMapping($expandName);

        return $association['targetEntity'] ?? null;
    }
}
