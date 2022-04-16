<?php

declare(strict_types=1);

abstract class AbstractDataMapper
{
    public function isEmpty(mixed $value, ?string $errorMsg = null) : bool
    {
        if (empty($value)) {
            throw new DataMapperExceptions($errorMsg);
        }
        return true;
    }

    public function isArray(array $value) : bool
    {
        if (!is_array($value)) {
            throw new DataMapperExceptions('Your argument need to be an array!');
        }
        return true;
    }

    protected function valueType(mixed $value) : int
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
     * Select Results
     * ===============================================================.
     * @param [type] $q
     * @param array $data
     * @return mixed
     */
    protected function select_result($q, array $data) : mixed
    {
        $type = $this->returnMode($data);
        $q = $this->fetchMode($type, $q, $data);
        $check = array_key_exists('return_type', $data) ? $data['return_type'] : 'all';
        return match ($check) {
            'count' => $q->rowCount(),
            'single' => $q->fetch(),
            'first' => current($q->fetchAll()),
            default => $q->fetchAll()
        };
    }

    private function fetchMode(int $type, PDOStatement $q, array $data) : PDOStatement
    {
        $className = isset($data['class']) ? $data['class'] : null;
        $contructorArgs = isset($data['constructorArgs']) ? $data['constructorArgs'] : null;
        if ($className != null) {
            if ($contructorArgs != null) {
                $q->setFetchMode($type, $className, $contructorArgs);
            } else {
                $q->setFetchMode($type, $className);
            }
        } else {
            $q->setFetchMode($type);
        }
        return $q;
    }

    /**
     * Get Result type
     * ================================================.
     * @param array $data
     * @return int
     */
    private function returnMode(array $data) : int
    {
        $returnMode = PDO::FETCH_DEFAULT;
        if (array_key_exists('return_mode', $data)) {
            $returnMode = match ($data['return_mode']) {
                'object' => PDO::FETCH_OBJ,
                'class' => PDO::FETCH_CLASS,
                default => PDO::FETCH_ASSOC
            };
        }
        return $returnMode;
    }
}