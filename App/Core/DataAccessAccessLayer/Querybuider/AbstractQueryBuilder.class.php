<?php

declare(strict_types=1);

abstract class AbstractQueryBuilder
{
    /** @var array */
    protected const SQL_DEFAULT = [
        'conditions' => [],
        'selectors' => [],
        'replace' => false,
        'distinct' => false,
        'from' => [],
        'where' => null,
        'and' => [],
        'or' => [],
        'orderby' => [],
        'fields' => [],
        'primary_key' => '',
        'table' => '',
        'type' => '',
        'raw' => '',
        'table_join' => '',
        'join_key' => '',
        'join' => [],
        'params' => [],
        'custom' => '',
    ];

    /** @var array */
    protected const QUERY_TYPES = ['insert', 'select', 'update', 'delete', 'custom', 'search', 'join', 'show', 'delete'];
    /** @var array */
    protected array $key;

    /** @var string */
    protected string $sql = '';

    public function isValidquerytype(string $type) : bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }
        return false;
    }

    protected function mainQuery() : string
    {
        $sql = '';
        if (!array_key_exists('sql', $this->key['extras'])) {
            if (strpos($this->key['table'], 'SELECT') !== false) {
                $sql = $this->key['table'];
            } else {
                $selectors = (!empty($this->key['selectors'])) ? implode(', ', $this->key['selectors']) : '*';
                if (isset($this->key['aggregate']) && $this->key['aggregate']) {
                    $sql = "SELECT {$this->key['aggregate']}({$this->key['aggregate_field']}) FROM {$this->key['table']}";
                } else {
                    $sql = "SELECT {$selectors} FROM {$this->key['table']}";
                }
            }
        } else {
            $sql = $this->key['extras']['sql'];
        }
        return $sql;
    }

    /**
     * Join Table when selecting data
     * =====================================================================.
     * @param mixed $tables
     * @param array $data
     * @return void
     */
    protected function join($selectors, array $options = []) :string
    {
        $sql = '';
        if (array_key_exists('join_rules', $options)) {
            if (array_key_exists('table', $options)) {
                if (!(count($options['join_rules']) === count($options['table']) - 1)) {
                    throw new QueryBuilderInvalidArgExceptions('Cannot join tables');
                }
                $columns = (!empty($selectors)) ? implode(', ', $selectors) : '*';
                $sql .= "SELECT {$columns} FROM {$options['table'][0]}";
            }
            $all_tables = $options['table'];
            foreach ($options['join_rules'] as $index => $join_rule) {
                $withParams = array_key_exists('params', $options['join_on'][$all_tables[$index + 1]]) ? true : false;
                $braceOpen = $withParams ? ' (' : '';
                $braceClose = $withParams ? ') ' : '';
                if (is_numeric($index)) {
                    $sql .= ' ' . $join_rule . ' ' . $all_tables[$index + 1];
                    $sql .= ' ON ' . $braceOpen . '(' . $options['join_on'][$all_tables[$index + 1]][1] . ' = ' . $options['join_on'][$all_tables[$index + 1]][0] . ')';
                }
                if ($withParams) {
                    $params = $options['join_on'][$all_tables[$index + 1]]['params'];
                    $args = $params[0];
                    $sql .= ' ' . $params['separator'] . ' (' . $args[0] . ' ' . $params['operator'] . ' ' . $this->getValue($args[1]) . ')' . $braceClose;
                }
            }
        }
        return $sql;
    }

    /**
     * Where condition
     * =====================================================================.
     *
     * @return string
     */
    protected function where() : string
    {
        $where = '';
        $whereCond = (is_array($this->key['where']) && !empty($this->key['where'])) ? array_merge($this->key['conditions'], $this->key['where']) : $this->key['conditions'];
        if (isset($whereCond) && !empty($whereCond)) {
            $where .= ' WHERE ';
            $i = 0;
            $where .= '(';
            foreach ($whereCond as $field => $aryCond) {
                if ($field != 'or' && $field != 'and') {
                    if (is_array($aryCond) && !empty($aryCond)) {
                        $where .= $this->whereConditions($aryCond, $field);
                        $i++;
                        unset($whereCond[$field]);
                    }
                }
            }
            $where .= ')';
            if (count($whereCond) > 0) {
                foreach ($whereCond as $separator => $aryConds) {
                    $where .= ' ' . strtoupper($separator) . ' (';
                    $i = 0;
                    foreach ($aryConds as $field => $AryCond) {
                        $where .= $this->whereConditions($AryCond, $field);
                        $i++;
                    }
                    $where .= ')';
                }
            }
        }
        return $where;
    }

    /**
     * Group By.
     *
     * @return void
     */
    protected function groupBy()
    {
        $groupBy = '';
        if (isset($this->key['extras']) && array_key_exists('group_by', $this->key['extras'])) {
            $groupBy .= ' GROUP BY ' . implode(', ', $this->key['extras']['group_by']);
        }
        return $groupBy . '';
    }

    protected function orderBy()
    {
        if (isset($this->key['extras']['orderby']) && $this->key['extras']['orderby'] != '') {
            $this->sql .= is_array($this->key['extras']['orderby']) ? ' ORDER BY ' . implode(', ', $this->key['extras']['orderby']) . ' ' : ' ORDER BY ' . $this->key['extras']['orderby'];
        }
    }

    protected function queryOffset()
    {
        // Append the limit and offset statement for adding pagination to the query
        if (isset($this->key['params']['limit']) && isset($this->key['params']['offset']) && $this->key['params']['offset'] != -1) {
            $this->sql .= ' LIMIT :offset, :limit';
        }
        if (isset($this->key['params']['limit']) && !isset($this->key['params']['offset'])) {
            $this->sql .= ' LIMIT :limit';
        }
    }

    protected function isQueryTypeValid(string $type) : bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }
        return false;
    }

    /**
     * Checks whether a key is set. returns true or false if not set.
     *
     * @param string $key
     * @return bool
     */
    protected function has(string $key): bool
    {
        return isset($this->key[$key]);
    }

    private function getValue(mixed $arg) : mixed
    {
        return match (gettype($arg)) {
            'int' => intval($arg),
            'bool' => boolval($arg),
            default => "'" . $arg . "'"
        };
    }

    private function whereConditions(array $aryCond, string $field) : string
    {
        $separator = isset($aryCond['separator']) ? ' ' . $aryCond['separator'] . ' ' : '';
        $operator = isset($aryCond['operator']) ? ' ' . $aryCond['operator'] . ' ' : '';
        $braceOpen = isset($aryCond['braceOpen']) ? ' ' . $aryCond['braceOpen'] . ' ' : '';
        $braceEnd = isset($aryCond['braceEnd']) ? ' ' . $aryCond['braceEnd'] . ' ' : '';
        switch ($operator) {
        case in_array(trim($operator), ['NOT IN', 'IN']):
            $arr = [];
            $prefixer = $this->arrayPrefixer($field, $aryCond['value'], $arr);
            $this->key['where']['bind_array'] = $arr;
            return $braceOpen . $aryCond['tbl'] . '.' . $field . $operator . ' (' . $prefixer . ')' . $braceEnd . $separator;
            // return "$add" . $tbl . $key . ' ' . $value['operator'] . ' (' . $prefixer . ')';

        break;

        default:
            return $braceOpen . $aryCond['tbl'] . '.' . $field . $operator . ":$field" . $braceEnd . $separator;
        break;
    }

        // case isset($value['operator']) && in_array($value['operator'], ['NOT IN', 'IN']):

        //     break;
    }

    /**
     * Array prefixer.
     *
     * @param string $prefix
     * @param array $values
     * @param array $bindArray
     * @return string
     */
    private function arrayPrefixer(string $prefix, array $values, array &$bindArray) : string
    {
        $str = '';
        foreach ($values as $index => $value) {
            $str .= ':' . $prefix . $index . ',';
            $bindArray[$prefix . $index] = $value;
        }

        return rtrim($str, ',');
    }
}