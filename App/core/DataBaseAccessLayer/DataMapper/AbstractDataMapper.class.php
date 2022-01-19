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

    /**
     * Select Results
     * ===============================================================.
     * @param [type] $q
     * @param array $data
     * @return mixed
     */
    protected function select_result($q, array $data) : mixed
    {
        $value = '';
        $type = $this->typeMode($data);
        if (!empty($type)) {
            isset($data['class']) ? $q->setFetchMode($type, $data['class'], $data['class_args'] ?? []) : $q->setFetchMode($type);
            if (array_key_exists('return_type', $data)) {
                switch ($data['return_type']) {
                    case 'count':
                        $value = $this->_count;
                    break;
                    case 'single':
                        $value = $q->fetch();
                    break;
                    case 'first':
                        $value = current($q->fetchAll());
                    break;
                    default:
                        $value = '';
                    break;
                }
            } else {
                $value = $q->fetchAll();
            }
        } else {
            $value = $q->fetchAll();
        }
        return $value;
    }

    /**
     * Get Result type
     * =========================================================================================================.
     * @param array $data
     * @return void
     */
    private function typeMode(array $data)
    {
        $type = '';
        if (array_key_exists('return_mode', $data)) {
            switch ($data['return_mode']) {
                case 'object':
                    $type = PDO::FETCH_OBJ;
                break;
                case 'class':
                    $type = PDO::FETCH_CLASS;
                break;
                default:
                    $type = PDO::FETCH_ASSOC;
                break;
            }
        }
        return $type;
    }
}