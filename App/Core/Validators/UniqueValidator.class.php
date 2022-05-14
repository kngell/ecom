<?php

declare(strict_types=1);
class UniqueValidator extends CustomValidator
{
    public function runValidation()
    {
        $field = (is_array($this->getField())) ? $this->getField()[0] : $this->getField();
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        $this->getModel()->getQueryParams()->reset();
        $query_params = $this->getModel()->table()->where([$field => $value])->return('class');
        $other = $this->getModel()->getAll($query_params);
        if ($other->count() <= 0) {
            return true;
        }
        if (( new ReflectionProperty($this->getModel()->getEntity(), $this->getModel()->getEntity()->getColId()))->isInitialized($this->getModel()->getEntity())) {
            foreach ($other->get_results() as $item) {
                if (isset($table_id) && $item->$table_id == $this->getModel()->getEntity()->getColId()) {
                    return true;
                }
            }
        }
        return !$other->count() >= 1;
    }
}