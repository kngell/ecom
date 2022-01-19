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

    /**
     * Join Table when selecting data
     * =====================================================================.
     * @param mixed $tables
     * @param array $data
     * @return void
     */
    protected function join($tables, array $data = []) :string
    {
        $sql = '';
        if (is_array($tables) && !empty($tables)) {
            $sql = 'SELECT ';
            $all_tables = array_keys($tables);
            foreach ($all_tables  as $table) {
                if ($tables[$table]) {
                    switch (true) {
                        case !is_array($tables[$table]):
                            $count = explode('|', $tables[$table]);
                            $sql .= $count[0] . '(' . $table . '.' . $count[1] . ') AS Number ';
                            break;
                        default:
                            foreach ($tables[$table] as $value) {
                                $separator = $table == end($all_tables) && $value == end($tables[$table]) ? ' ' : ', ';
                                if (strpos($value, 'AS') !== false) {
                                    $v = explode('AS', $value);
                                    $sql .= $table . '.' . trim($v[0]) . ' AS ' . trim($v[1]) . $separator;
                                } else {
                                    $sql .= $table . '.' . $value . $separator;
                                }
                            }
                            break;
                    }
                }
            }
            $sql .= 'FROM ' . $all_tables[0] . ' ';
            $i = 0;
            $op = isset($data['op']) ? $data['op'] : ' AND ';
            if (array_key_exists('join', $data)) {
                foreach ($data['rel'] as $index => $value) {
                    $add = ($i > 0) ? ' ' . $op . ' ' : '';
                    if (is_numeric($index)) {
                        $sql .= $data['join'] . ' ' . $all_tables[$index + 1] . ' ON ';
                        $part1 = is_array($value[0]) ? $value[0]['tbl'] . '.' . $value[0]['value'] : $all_tables[$index] . '.' . $value[0];
                        $part2 = is_array($value[1]) ? $value[1]['tbl'] . '.' . $value[1]['value'] : $all_tables[$index + 1] . '.' . $value[1];
                        $sql .= $part1 . ' = ' . $part2 . ' ';
                    }
                    if ($index == 'params') {
                        foreach ($data['rel']['params'] as $key => $value) {
                            $params = explode('|', $value);
                            $sql .= $add . $params[2] . '.' . $params[0] . ' ' . $params[1] . ' ';
                        }
                    }
                    $i++;
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
            $op = isset($this->key['extras']['op']) ? $this->key['extras']['op'] : ' AND ';
            foreach ($whereCond as $key => $value) {
                $add = ($i > 0) ? ' ' . $op . ' ' : '';
                if (is_array($value)) {
                    $tbl = isset($value['tbl']) ? $value['tbl'] . '.' : '';
                    switch (true) {
                        case isset($value['operator']) && in_array($value['operator'], ['NOT IN', 'IN']):
                            $arr = [];
                            $where .= "$add" . $tbl . $key . ' ' . $value['operator'] . ' (' . $this->arrayPrefixer($key, $value['value'], $arr) . ')'; //":$key"
                            $this->key['where']['bind_array'] = $arr;
                        break;
                        case isset($value['operator']) && in_array($value['operator'], ['!=', '>', '<', '>=', '<=']):
                            $where .= "$add" . $tbl . $key . $value['operator'] . ":$key";
                        break;

                        default:
                            $where .= "$add" . $tbl . $key . '=' . ":$key";
                        break;
                      }
                } else {
                    $where .= $value == 'IS NULL' ? "$add" . $key . ' ' . 'IS NULL' : "$add" . $key . '=' . ":$key";
                }
                $i++;
            }
            if (isset($whereCond['op']) || isset($whereCond['comparator'])) {
                unset($whereCond['op'],$whereCond['comparator']);
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
            $groupBy .= ' GROUP BY ';
            $i = 0;
            $op = isset($this->key['extras']['op']) ? $this->key['extras']['op'] : ' AND ';
            if (is_array($this->key['extras']['group_by'])) {
                foreach ($this->key['extras']['group_by'] as $key => $value) {
                    $add = ($i > 0) ? ' ' . $op . ' ' : '';
                    if (is_array($value)) {
                        $tbl = isset($value['tbl']) ? $value['tbl'] . '.' : '';
                        $groupBy .= "$add" . $tbl . $key;
                    } else {
                        $groupBy .= "$add" . $this->key['extras']['group_by'][$key];
                    }
                    $i++;
                }
            } else {
                $groupBy .= $this->key['extras']['group_by'];
            }
            unset($this->key['extras']['group_by']);
        }

        return $groupBy;
    }

    protected function orderBy()
    {
        // Append the orderby statement if set
        if (isset($this->key['extras']['orderby']) && $this->key['extras']['orderby'] != '') {
            $this->key['orderby'] = array_merge($this->key['orderby'], $this->key['extras']['orderby']);
        }
        if (isset($this->key['orderby']) && !empty($this->key['orderby'])) {
            $this->sql .= ' ORDER BY ' . $this->key['orderby'] . ' ';
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