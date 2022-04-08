<?php

declare(strict_types=1);
class UniqueValidator extends CustomValidator
{
    public function runValidation()
    {
        $fields = (is_array($this->field)) ? $this->field[0] : $this->field;
        $value = $this->_model->{$fields};

        if (is_array($this->rule)) {
            $table = $this->rule[0];
            $table_id = $this->rule[1];
        } else {
            $table = $this->_model->rule;
        }
        $where = [$fields => $value];
        $query_params = ['where' => $where, 'return_mode' => 'class'];
        $other = $this->_model->getAllItem($query_params);
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

        // if(!empty($this->_model->id)){
        //     $conditions[] = " id != ?";
        //     $bind = $this->_model->id;
        // }

        //if (is_array($this->field)) {
        //array_unshift($this->field);
        //foreach ($this->field as $adds) {
        //    $conditions[] = "{}$adds = ?";
        //     $bind[] = $this->_model->{$adds};
        //  }
        //}
        //$queryparams = ['conditions'=>$conditions,'bind'=>$bind];
        //$other = $this->findfirst($queryparams);
        //retunr !$other;
        return !$other;
    }
}
