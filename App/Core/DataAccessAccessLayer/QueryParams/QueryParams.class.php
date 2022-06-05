<?php

declare(strict_types=1);

class QueryParams extends AbstractQueryParams
{
    public function __construct(string $tableSchema)
    {
        $this->tableSchema = $tableSchema;
    }

    public function table(?string $tbl = null, mixed $columns = null) : self
    {
        $this->reset();
        $tbl = $this->parseTable($tbl);
        $this->query_params['table_join'] = [$tbl != null ? $tbl : $this->tableSchema => $columns != null ? $columns : ['*']];
        $this->addTableToOptions($tbl);
        return $this;
    }

    public function params(?string $repositoryMethod = null) : array
    {
        $this->getSelectors();
        return match ($repositoryMethod) {
            'findOneBy' => [$this->query_params['conditions'] ?? [],  $this->query_params['options'] ?? []],
            'findBy' => [$this->query_params['selectors'] ?? [], $this->query_params['conditions'] ?? [], $this->query_params['parameters'] ?? [], $this->query_params['options'] ?? []],
            'delete','update' => [$this->query_params['conditions'] ?? []],
        };
    }

    public function join(?string $tbl = null, mixed $columns = null, string $joinType = 'INNER JOIN') : self
    {
        $tbl = $this->parseTable($tbl);
        $this->key('table_join');
        if (!array_key_exists($tbl, $this->query_params['table_join'])) {
            $this->query_params['table_join'] += [$tbl != null ? $tbl : $this->tableSchema => $columns != null ? $columns : ['*']];
            $this->key('options');
            $this->query_params['options']['join_rules'][] = $joinType;
            $this->addTableToOptions($tbl);
            return $this;
        }
        throw new Exception('Cannot join the same table ' . $tbl);
    }

    public function leftJoin(?string $tbl = null, mixed $columns = null) : self
    {
        return $this->join($tbl, $columns, 'LEFT JOIN');
    }

    public function rightJoin(?string $tbl = null, mixed $columns = null) : self
    {
        return $this->join($tbl, $columns, 'RIGHT JOIN');
    }

    public function on(...$params) : self
    {
        $this->key('options');
        if (!array_key_exists('join_on', $this->query_params['options'])) {
            $this->query_params['options']['join_on'] = [];
        }
        //$args = func_get_args();
        $tableIndex = 0;
        //$on = '';
        foreach ($params as $key => $join_params) {
            if (is_array($join_params) && !empty($join_params)) {
                foreach ($join_params as $k => $arg) {
                    if (is_array($arg)) {
                        $this->getParams($k, $arg);
                    } else {
                        $this->getJoinOptions($tableIndex + $k, $arg);
                    }
                }
                $tableIndex++;
            }
        }
        return $this;
    }

    public function where(array $conditions, ?string $op = null) : self
    {
        if (isset($conditions) && !empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereParams = $this->whereParams($conditions, $key, $value);
                if (is_string($key)) {
                    list($whereParams['field'], $whereParams['operator']) = $this->fieldOperator($key);
                    is_null($op) ? $this->query_params['conditions'] += $this->condition($whereParams) : $this->query_params['conditions'][$op] += $this->condition($whereParams);
                    $this->conditionBreak = [];
                } else {
                    $this->conditionBreak = $whereParams;
                }
            }
            return $this;
        }
    }

    public function and(array $cond, string $op = 'and') : self
    {
        if (isset($cond) && !empty($cond)) {
            if (!array_key_exists($op, $this->query_params['conditions'])) {
                $this->query_params['conditions'][$op] = [];
            }
            return $this->where($cond, $op);
        }
    }

    public function or(array $cond) : self
    {
        return $this->and($cond, 'or');
    }

    public function build() : array
    {
        return $this->query_params;
    }

    public function groupBy(array $groupByAry) : self
    {
        $this->key('options');
        foreach ($groupByAry as $field => $tbl) {
            if (is_numeric($tbl)) {
                $this->query_params['options']['group_by'][] = $field;
            } else {
                $this->query_params['options']['group_by'][] = $tbl . '.' . $field;
            }
        }
        return $this;
    }

    public function orderBy(array $orderByAry) : self
    {
        $this->key('options');
        foreach ($orderByAry as $tbl => $field) {
            $tbl = is_numeric($tbl) ? $this->query_params['options']['table'][0] : $tbl;
            if (str_contains($field, '|')) {
                $parts = explode('|', $field);
                if (is_array($parts)) {
                    $this->query_params['options']['orderby'][] = is_numeric($tbl) ? $this->current_table . '.' . $parts[0] . ' ' . $parts[1] : $tbl . '.' . $parts[0] . ' ' . $parts[1];
                }
            } else {
                $this->query_params['options']['orderby'][] = $tbl . '.' . $field;
            }
        }
        return $this;
    }

    public function return(string $str) : self
    {
        $this->key('options');
        $this->query_params['options']['return_mode'] = $str;
        return $this;
    }

    public function parameters(array $params) : self
    {
        if (!array_key_exists('parameters', $this->query_params)) {
            $this->query_params['parameters'] = [];
        }
        return $this->aryParams($params, 'parameters');
    }

    public function recursive(string $parentID, string $id) : self
    {
        $this->key('options');
        $this->query_params['options']['recursive']['parentID'] = $parentID;
        $this->query_params['options']['recursive']['id'] = $id;
        $this->recursiveCount();
        return $this;
    }

    private function reset() : self
    {
        $this->query_params = [];
        $this->conditionBreak = [];
        $this->braceOpen = '';
        return $this;
    }

    private function aryParams(array $params, string $name) : self
    {
        if (isset($params) && !empty($params)) {
            $this->query_params[$name] = $params;
        }
        return $this;
    }
}