<?php

declare(strict_types=1);
abstract class AbstractQueryParams implements QueryParamsInterface
{
    protected const SEPARATOR = ['OR', 'XOR'];
    protected const OPERATOR = ['!=', '=', '<=', '>=', 'IN', 'NOT IN'];
    protected string $current_table = '';
    protected string $tableSchema;
    /** @var array */
    protected array $query_params = [];
    /** @var array */
    protected array $conditionBreak = [];
    private string $braceOpen = '';

    public function hasConditions() : bool
    {
        return (isset($this->query_params['conditions']) && $this->query_params['conditions'] != []) ? true : false;
    }

    public function reset() : self
    {
        $this->query_params = [];
        return $this;
    }

    protected function separator(mixed $separator, mixed $key) : string
    {
        if (is_string($separator) && is_numeric($key) && in_array(strtoupper($separator), self::SEPARATOR)) {
            return strtoupper($separator);
        }
        return 'AND';
    }

    protected function fieldOperator(string $field) : array
    {
        if (str_contains($field, '|')) {
            $parts = explode('|', $field);
            $operator = '';
            foreach ($parts as $k => $part) {
                if (in_array(strtoupper($part), self::OPERATOR)) {
                    $operator = strtoupper($parts[$k]);
                    unset($parts[$k]);
                }
            }
            if (!count($parts) === 1) {
                throw new BaseInvalidArgumentException('Argument ou condition mal renseignée');
            }
            return [current($parts), $operator];
        }
        return [$field, '='];
    }

    protected function condition(array $params) : array
    {
        $where = [];
        $this->key('conditions');
        if (is_string($params['field'])) {
            $where[$params['field']] = ['value' => $params['value'], 'tbl' => $this->current_table];
        }
        if ($params['operator'] != '') {
            $where[$params['field']]['operator'] = $params['operator'];
        }
        if ($params['separator'] != '') {
            $where[$params['field']]['separator'] = $params['separator'];
        }
        if ($params['braceOpen'] != '') {
            $where[$params['field']]['braceOpen'] = $params['braceOpen'];
        }
        if ($params['braceEnd'] != '') {
            $where[$params['field']]['braceEnd'] = $params['braceEnd'];
        }
        return $where;
    }

    protected function addTableToOptions(?string $tbl = null) : void
    {
        $tbl == null ? $tbl = $this->tableSchema : '';
        $this->key('options');
        if (!array_key_exists('table', $this->query_params['options'])) {
            $this->query_params['options']['table'] = [];
        }
        $this->query_params['options']['table'][] = $tbl;
        $this->current_table = $tbl;
    }

    protected function key(string $key) : void
    {
        if (!array_key_exists($key, $this->query_params)) {
            $this->query_params[$key] = [];
        }
    }

    protected function braceOpen(array $conditions) : string
    {
        $prevCondition = isset($this->query_params['conditions']) ? current($this->query_params['conditions']) : [];
        if (count($conditions) > 2 || (isset($prevCondition['separator']) && in_array($prevCondition['separator'], self::SEPARATOR))) {
            return $this->braceOpen = '(';
        }
        return '';
    }

    protected function braceEnd(string $separator, mixed $key) : string
    {
        if (!empty($this->braceOpen) && ((is_numeric($key) || in_array($separator, self::SEPARATOR)) || !empty($this->conditionBreak))) {
            $this->braceOpen = '';
            return ')';
        }
        return '';
    }

    protected function whereParams(array $conditions, mixed $key, mixed $value) : array
    {
        $whereParams = [];
        $lastKey = array_key_last($conditions);
        $firstKey = key($conditions);
        $whereParams['separator'] = ($key != $lastKey) ? $this->separator(next($conditions), key($conditions)) : '';
        $whereParams['braceOpen'] = ($key == $firstKey) || (is_numeric($key) && in_array($value, ['or', 'and']) || !empty($this->conditionBreak)) ? $this->braceOpen($conditions) : '';
        $whereParams['braceEnd'] = $this->braceEnd($whereParams['separator'], $key);
        $whereParams['value'] = $value;
        return $whereParams;
    }
}