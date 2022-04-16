<?php

declare(strict_types=1);
class UniqueValidator extends CustomValidator
{
    public function runValidation()
    {
        $field = (is_array($this->getField())) ? $this->getField()[0] : $this->getField();
        $value = $this->getModel()->{$field};

        if (is_array($this->getRule())) {
            $table = $this->getRule()[0];
            $table_id = $this->getRule()[1];
        } else {
            $table = $this->getModel()->rule;
        }
        $where = [$field => $value];
        $query_params = $this->getModel()->table()->where($where)->return('class');
        $other = $this->getModel()->getAll($query_params);
        if ($other->count() <= 0) {
            return true;
        }
        if (property_exists($this->_model, 'id')) {
            foreach ($other->get_results() as $item) {
                if (isset($table_id) && $item->$table_id == $this->_model->id) {
                    return true;
                }
            }
        }
        return !$other;
    }
}