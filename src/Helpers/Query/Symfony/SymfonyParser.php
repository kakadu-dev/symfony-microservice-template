<?php

namespace App\Helpers\Query\Symfony;

use Exception;

/**
 * Class SymfonyJsonParser
 * @package App\Helpers\Query\Symfony
 */
class SymfonyParser implements SymfonyParserInterface
{
    public const OPERATORS = [
        'BETWEEN'          => 'between',
        'LIKE'             => 'like',
        'IN'               => 'in',
        'NOT_IN'           => '!in',
        'NOT_EQUAL'        => '!=',
        'OR'               => 'or',
        'AND'              => 'and',
        'GREATER'          => '>',
        'GREATER_OR_EQUAL' => '>=',
        'LESS'             => '<',
        'LESS_OR_EQUAL'    => '<=',
    ];

    private const BINDER_OPERATOR = [
        'OR'  => 'or',
        'AND' => 'and',
    ];

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @var string
     */
    private string $modelName;

    /**
     * @var string|null
     */
    private ?string $condition = null;
    
    /**
     * @param string $modelName
     *
     * @return $this
     */
    public function setModelName(string $modelName): self
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * @param array $condition
     *
     * @return array|mixed|string|string[]
     * @throws Exception
     */
    public function prepareCondition(array $condition)
    {
        if (empty($condition)) {
            return [];
        }

        $result = [];

        foreach ($condition as $key => $value) {

            if ($this->isBinderOperator($key)) {
                if (!is_array($value) || count($value) <= 1) {
                    throw new Exception("Expected type 'array' for {$key} operator");
                }

                $cyclePrepareCondition = array_map(function ($item) use ($key) {
                    $cycleResult = $this->prepareCondition($item);

                    if (is_string($cycleResult)) {
                        return '(' . $cycleResult . ')';
                    }

                    return $cycleResult;
                }, $value);

                $result[] = $this->getImplodeCondition($cyclePrepareCondition, $key);
                continue;
            }

            if (is_array($value)) {
                if (!empty($result)) {
                    $result = [$result[0] . ' and ' . implode(' ', $this->parseCondition($key, $value))];
                } else {
                    $result[] = implode(' ', $this->parseCondition($key, $value));
                }
                continue;
            }

            $uidParameter = uniqid();
            $parameter = "{$this->getParameterName($key)}_{$uidParameter}";
            $this->parameters[$parameter] = $value;
            if (!empty($result)) {
                $result = [$result[0] . ' and ' . "{$this->getFieldName($key)} = :$parameter"];
            } else {
                $result[] = "{$this->getFieldName($key)} = :$parameter";
            }

        }

        if (count($result) === 1 && is_string($result[0])) {
            return array_shift($result);
        }

        return $result;
    }

    /**
     * @param array $condition
     *
     * @return $this
     * @throws Exception
     */
    public function parseJson(array $condition): self
    {
        if (empty($condition)) {
            return $this;
        }

        $condition = $this->prepareCondition($condition);

        if (is_array($condition)) {
            $condition = implode(' and ', $condition);
        }
        
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCondition(): ?string
    {
        return $this->condition;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param $data
     * @param $glue
     *
     * @return array|string
     */
    public function getImplodeCondition($data, $glue)
    {
        if ($this->isAvailable($data)) {
            return '(' . implode(" $glue ", $data) . ')';
        }

        $result = [];
        foreach ($data as $item) {
            if (!is_array($item)) {
                $result[] = $item;
                continue;
            }

            $result[] = $this->getImplodeCondition($item, $glue);
        }

        if (count($result) >= 2) {
            $result = $this->getImplodeCondition($result, $glue);
        }

        return $result;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function isAvailable($data): bool
    {
        if (!is_array($data)) {
            return false;
        }

        foreach ($data as $condition) {
            if (!is_string($condition)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getFieldName(string $key): string
    {
        if (strpos($key, '.') !== false) {
            return $key;
        }

        return "{$this->modelName}.{$key}";
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getParameterName(string $key): string
    {
        return str_replace('.', '_', $this->getFieldName($key));
    }

    /**
     * @param $key
     *
     * @return bool
     */
    private function isBinderOperator($key): bool
    {
        return in_array($key, self::BINDER_OPERATOR);
    }

    /**
     * @param $key
     * @param $condition
     *
     * @return array
     * @throws Exception
     */
    private function parseCondition($key, $condition): array
    {
        if (($count = count($condition)) === 0 || $count > 2) {
            throw new Exception("Operator '{$key}' has '{$count}' keys");
        }

        $firstOperator  = array_keys($condition)[0];
        $secondOperator = array_keys($condition)[1] ?? null;

        switch ($firstOperator) {
            case self::OPERATORS['BETWEEN']:
                return [
                    $this->getFieldName($key),
                    $firstOperator,
                    $condition[$firstOperator][0],
                    self::OPERATORS['AND'],
                    $condition[$firstOperator][1],
                ];
            case self::OPERATORS['LIKE']:
                $uidParameter = uniqid();
                $parameter = "{$this->getParameterName($key)}_{$uidParameter}";

                $this->parameters[$parameter] = $condition[$firstOperator];

                return [$this->getFieldName($key), $firstOperator, ":$parameter"];
            case self::OPERATORS['IN']:
            case self::OPERATORS['NOT_IN']:
                $values = '(' . implode(', ', $condition[$firstOperator]) . ')';

                return [$this->getFieldName($key), $firstOperator, $values];
            case self::OPERATORS['NOT_EQUAL']:
                $uidParameter = uniqid();
                $parameter = "{$this->getParameterName($key)}_{$uidParameter}";

                $this->parameters[$parameter] = $condition[$firstOperator];

                return [$this->getFieldName($key), '<>', ":{$parameter}"];
            case self::OPERATORS['OR']:
                $result = [];
                foreach ($condition[$firstOperator] as $item) {
                    if (!empty($result)) {
                        $result[] = $firstOperator;
                    }
                    $uidParameter = uniqid();
                    $parameter = "{$this->getParameterName($key)}_{$uidParameter}";

                    $this->parameters[$parameter] = $item;

                    $result[] = "{$this->getFieldName($key)} = :{$parameter}";
                }

                return $result;
            case self::OPERATORS['GREATER']:
            case self::OPERATORS['GREATER_OR_EQUAL']:
            case self::OPERATORS['LESS']:
            case self::OPERATORS['LESS_OR_EQUAL']:
                $result = [];

                foreach ($condition as $operator => $value) {
                    $result[] = $this->getFieldName($key) . ' ' . $operator . ' ' . $value;
                    if ($secondOperator && count($result) === 1) {
                        $result[] = self::OPERATORS['AND'];
                    }
                }

                return $result;
        }

        throw new Exception("Undefined operator: {$firstOperator}");
    }
}
