<?php

declare(strict_types=1);
class ModelCruds extends ModelHelper
{
    public function findFirst($params = []) : ?self
    {
        if (isset($params['return_mode']) && $params['return_mode'] == 'class' && !isset($params['class'])) {
            $params = array_merge($params, ['class' => get_class($this)]);
        }
        $cond = $params['where'];
        unset($params['where']);
        $dataMapperResults = $this->repository->findOneBy($cond, $params);
        if ($dataMapperResults->count() <= 0) {
            $this->_count = 0;
            return $this;
        }
        $this->_count = $dataMapperResults->count();
        $this->_results = $this->afterFind($dataMapperResults)->get_results();
        return $this;
    }
}
