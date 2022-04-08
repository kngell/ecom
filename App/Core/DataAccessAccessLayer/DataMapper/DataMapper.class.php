<?php

declare(strict_types=1);

use Throwable;

class DataMapper extends AbstractDataMapper implements DataMapperInterface
{
    private PDOStatement $_query;

    private int $_count = 0;
    private $_results;
    private $bind_arr = [];
    private DatabaseConnexionInterface $_con;
    private Entity $entity;

    /**
     * Set Database connection
     * ===================================================================.
     */
    public function __construct(DatabaseConnexionInterface $_con, Entity $entity)
    {
        $this->_con = $_con;
        $this->entity = $entity;
    }

    /**
     *@inheritDoc
     */
    public function prepare(string $sql):self
    {
        $this->_query = $this->_con->open()->prepare($sql);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bind_type(mixed $value) : int
    {
        try {
            switch ($value) {
            case is_bool($value):
            case intval($value):
                $type = PDO::PARAM_INT;
            break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
            break;
            default:
                $type = PDO::PARAM_STR;
            break;
        }
            return $type;
        } catch (\DataMapperExceptions $ex) {
            throw $ex;
        }
    }

    /**
     * @inheritDoc
     */
    public function bind($param, $value, $type = null)
    {
        switch ($type === null) {
            case is_int($value):
                $type = PDO::PARAM_INT;
            break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
            break;
            case $value === null:
                $type = PDO::PARAM_NULL;
            break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->_query->bindValue($param, $value, $type);
    }

    /**
     * Bind an Array of Values.
     * ==============================================================.
     * @param array $fields
     * @return void
     */
    public function bindArrayValues(array $fields) : PDOStatement
    {
        if ($this->isArray($fields)) {
            foreach ($fields as $key => $value) {
                $this->_query->bindValue(':' . $key, $value, $this->bind_type($value));
            }
        }
        return $this->_query;
    }

    /**
     * Bind Parameters
     * ==============================================================.
     * @inheritDoc
     */
    public function bindParameters(array $fields = [], bool $isSearch = false) : self
    {
        if ($this->isArray($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->biendSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Biend Values
     * ===============================================================.
     * @param array $fields
     * @return PDOStatement
     * @throws DataMapperExceptions
     */
    public function bindValues(array $fields = []) : PDOStatement
    {
        if (!empty($fields)) {
            if (isset($fields['bind_array'])) {
                unset($fields['bind_array']);
            }
            foreach ($fields as $key => $val) {
                if (in_array($key, ['and', 'or'])) {
                    $val = current($val);
                }
                if (is_array($val)) {
                    switch (true) {
                        case isset($val['operator']) && in_array($val['operator'], ['=', '!=', '>', '<', '>=', '<=']):
                            $this->bind(":$key", $val['value']);
                            break;
                        case isset($val['operator']) && in_array($val['operator'], ['NOT IN', 'IN']):
                            if (!empty($this->bind_arr)) {
                                foreach ($this->bind_arr as $k => $v) {
                                    $this->bind(":$k", $v);
                                }
                            }
                            break;
                        default:
                            $this->bind(":$key", $val['value']);
                            break;
                    }
                } else {
                    $val != 'IS NULL' ? $this->bind(":$key", $val) : '';
                }
            }
        }

        return $this->_query;
    }

    /**
     * Bind search values
     * =================================================================.
     * @param array $fields
     */
    public function biendSearchValues(array $fields = [])
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->_query->bindValue(':' . $key, '%' . $value . '%', $this->bind_type($value));
        }

        return $this->_query;
    }

    /**
     * Get numberof row
     * ============================================================.
     *@inheritDoc
     */
    public function numrow(): int
    {
        if ($this->_query) {
            return $this->_count = $this->_query->rowCount();
        }
    }

    /**
     * Execute
     * =============================================================.
     *@inheritDoc
     */
    public function execute(): mixed
    {
        if ($this->_query) {
            return $this->_query->execute();
        }
    }

    /**
     * Single results as object
     * =================================================================.
     *@inheritDoc
     */
    public function result(): Object
    {
        if ($this->_query) {
            return $this->_query->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * Results
     * =======================================================================.
     *@inheritDoc
     */
    public function results(array $options = []) : self
    {
        if ($this->_query) {
            $this->_results = $this->select_result($this->_query, $options);
            return $this;
        }
    }

    /**
     *  Get las insert ID
     * ======================================================================.
     *   *@inheritDoc
     */
    public function getLasID(): int
    {
        try {
            if ($this->_con->open()) {
                $lastID = $this->_con->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * persist Method
     * =======================================================================.
     * @param string $sql
     * @param array $parameters
     */
    public function persist(string $sql = '', array $parameters = [])
    {
        try {
            $sql = $this->cleanSql($sql);

            return isset($parameters[0]) && $parameters[0] == 'all' ? $this->prepare($sql)->execute() : $this->prepare($sql)->bindParameters($parameters)->execute();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Build Query parametters
     * =======================================================================.
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || !empty($conditions)) ? array_merge($parameters, $conditions) : $parameters;
    }

    /**
     * @inheritDoc
     */
    public function column()
    {
        if ($this->_query) {
            return $this->_query->fetchColumn();
        }
    }

    public function count()
    {
        return $this->_count;
    }

    public function get_results()
    {
        return $this->_results;
    }

    public function set_results(mixed $results) : self
    {
        $this->_results = $results;
        return $this;
    }

    public function cleanSql(string $sql)
    {
        $sqlArr = explode('&', $sql);
        if (isset($sqlArr) & count($sqlArr) > 1) {
            $this->bind_arr = unserialize($sqlArr[1]);
        }

        return $sqlArr[0];
    }
}