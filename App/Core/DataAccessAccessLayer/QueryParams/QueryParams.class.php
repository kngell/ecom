<?php

declare(strict_types=1);

class QueryParams extends AbstractQueryParams
{
    public function __construct(string $tableSchema)
    {
        $this->tableSchema = $tableSchema;
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
    //SELECT a.id, IFNULL(b.name, c.name)
    // FROM tableA AS a
    // LEFT JOIN tableB AS b ON a.id = b.id
    // LEFT JOIN tableC AS c ON a.id = c.id
    // WHERE
    // b.name = c.name OR b.name IS NULL OR c.name IS NULL;

    public function table(?string $tbl = null, mixed $columns = null) : self
    {
        $this->query_params['table_join'] = [$tbl != null ? $tbl : $this->tableSchema => $columns != null ? $columns : ['*']];
        $this->addTableToOptions($tbl);
        return $this;
    }

    public function join(?string $tbl = null, mixed $columns = null, string $joinType = 'INNER JOIN') : self
    {
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

    public function on(array $columns, array $params = []) : self
    {
        $this->key('options');
        if (!array_key_exists('join_on', $this->query_params['options'])) {
            $this->query_params['options']['join_on'] = [];
        }
        if (!array_key_exists($this->current_table, $this->query_params['options']['join_on'])) {
            $this->query_params['options']['join_on'][$this->current_table] = [];
        }
        $args = func_get_args();
        $op = '';
        foreach ($args as $key => $join_params) {
            if (is_array($join_params) && !empty($join_params)) {
                foreach ($join_params as $k => $arg) {
                    if (is_string($k) && str_contains($k, '|')) {
                        $this->getParams($k, $arg);
                    } else {
                        $tbl = is_numeric($k) ? $this->current_table : $k;
                        array_push($this->query_params['options']['join_on'][$this->current_table], $tbl . '.' . $arg);
                    }
                }
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
        foreach ($groupByAry as $tbl => $field) {
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

    public function parameters(array $params) : self
    {
        if (!array_key_exists('parameters', $this->query_params)) {
            $this->query_params['parameters'] = [];
        }
        return $this->aryParams($params, 'parameters');
    }

    public function return(string $str) : self
    {
        if (!array_key_exists('options', $this->query_params)) {
            $this->query_params['options'] = [];
        }
        $this->query_params['options']['return_mode'] = $str;
        return $this;
    }

    private function getParams(string $k, mixed $arg) : void
    {
        $parts = is_string($k) ? explode('|', $k) : '';
        $field = $parts[0] == 'or' ? $parts[1] : $parts[0];
        if (!array_key_exists('params', $this->query_params['options']['join_on'][$this->current_table])) {
            $this->query_params['options']['join_on'][$this->current_table]['params'] = [];
        }
        $tbl = is_array($arg) ? $arg[1] : $this->current_table;
        $value = is_array($arg) ? $arg[0] : $arg;
        array_push($this->query_params['options']['join_on'][$this->current_table]['params'], [$tbl . '.' . $field, $value]);
        $this->query_params['options']['join_on'][$this->current_table]['params']['separator'] = $parts[0] == 'or' ? 'OR' : 'AND';
        $this->query_params['options']['join_on'][$this->current_table]['params']['operator'] = $parts[0] == 'or' ? $parts[2] : $parts[1];
    }

    private function getSelectors() : array
    {
        $selectors = [];
        $this->key('selectors');
        if (array_key_exists('table_join', $this->query_params)) {
            foreach ($this->query_params['table_join'] as $tbl => $columns) {
                if (!is_array($columns)) {
                    throw new Exception('Columns must be in array!');
                }
                foreach ($columns as $column) {
                    if (str_contains($column, '|')) {
                        $parts = explode('|', $column);
                        if (is_array($parts) && count($parts) < 3) {
                            array_push($selectors, $tbl . '.' . $parts[1] . '(' . $parts[0] . ')');
                        } else {
                            array_push($selectors, $parts[1] . '(' . $tbl . '.' . $parts[0] . ') AS ' . $parts[2]);
                        }
                    } else {
                        array_push($selectors, $tbl . '.' . $column);
                    }
                }
            }
        }
        return $this->query_params['selectors'] = $selectors;
    }

    private function keyExists(string $key) : bool
    {
        if (!array_key_exists($key, $this->query_params)) {
            throw new Exception($key . ' does not exists ');
        }
        return true;
    }

    private function aryParams(array $params, string $name) : self
    {
        if (isset($params) && !empty($params)) {
            $this->query_params[$name] = $params;
        }
        return $this;
    }
}