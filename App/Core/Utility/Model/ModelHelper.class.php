<?php

declare(strict_types=1);
class ModelHelper
{
    public function getRepoArgs(array $data = [], array $tables = []) : array
    {
        $results = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    throw new BaseInvalidArgumentException($value . ' must be an array');
                }
                if (in_array($key, array_keys($data))) {
                    if ($key == 'table_join') {
                        $results[$key] = array_merge($value, $tables);
                    } else {
                        $results[$key] = $value;
                    }
                }
            }
        }
        return [$results['selectors'] ?? [], $results['conditions'] ?? [], $results['parameters'] ?? [], $results['options'] ?? ''];
    }
}