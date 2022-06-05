<?php

declare(strict_types=1);
trait ModelTrait
{
    public function find() : Model
    {
        list($selectors, $conditions, $parameters, $options) = $this->queryParams->params('findBy');
        if (isset($options['return_mode']) && $options['return_mode'] == 'class' && !isset($options['class'])) {
            $options = array_merge($options, ['class' => $this->getModelName()]);
        }
        $results = $this->repository->findBy($selectors, $conditions, $parameters, $options);
        $this->setResults($results->count() > 0 ? $results->get_results() : null);
        $this->setCount($results->count() > 0 ? $results->count() : 0);
        $results = null;
        return $this;
    }

    public function findFirst() : Model
    {
        list($conditions, $options) = $this->queryParams->params('findOneBy');
        if (isset($options['return_mode']) && $options['return_mode'] == 'class' && !isset($options['class'])) {
            $options = array_merge($options, ['class' => get_class($this)]);
        }
        $dataMapperResults = $this->repository->findOneBy($conditions, $options);
        if ($dataMapperResults->count() <= 0) {
            $this->setCount(0);
            return $this;
        }
        $this->setCount($dataMapperResults->count());
        $this->setResults($this->afterFind($dataMapperResults)->get_results());
        return $this;
    }

    public function insert() : self
    {
        $lastID = $this->getRepository()->entity($this->getEntity())->create();
        $this->setCount($lastID ?? 0);
        $this->setLastID($lastID ?? 0);
        return $this;
    }

    public function update() : self
    {
        list($conditions) = $this->conditions()->getQueryParams()->params('update');
        $this->getEntity()->delete($this->getEntity()->regenerateField($this->getEntity()->getColID()));
        $this->setCount($this->getRepository()->entity($this->getEntity())->update($conditions));
        return $this;
    }

    public function delete() : self
    {
        list($conditions) = $this->conditions()->getQueryParams()->params('delete');
        $this->setCount($this->getRepository()->entity($this->getEntity())->delete($conditions));
        return $this;
    }

    /**
     * After Find
     * =============================================================.
     * @param object $m
     * @return DataMapper
     */
    public function afterFind(?DataMapper $m = null) : DataMapper
    {
        $this->assign((array) current($m->get_results()));
        if ($m->count() === 1) {
            $model = current($m->get_results());
            $array = false;
            if (is_array($model)) {
                $array = true;
                $model = (object) $model;
            }
            $media_key = $this->getEntity()->getFieldWithDoc('media');
            if ($media_key != '') {
                $model->$media_key = $model->$media_key != null ? unserialize($model->$media_key) : ['products' . US . 'product-80x80.jpg'];
                if (is_array($model->$media_key)) {
                    foreach ($model->$media_key as $key => $url) {
                        $model->$media_key[$key] = IMG . $url; //
                    }
                }
            }
            $m->set_results($array ? (array) $model : $model);
        }
        return $m;
    }

    public function get_media() : string
    {
        return isset($this->_media_img) ? $this->_media_img : '';
    }

    private function properties() : void
    {
        $this->container = property_exists($this, 'container') ? Container::getInstance() : '';
        $props = array_merge(['entity' => str_replace(' ', '', ucwords(str_replace('_', ' ', $this->tableSchema))) . 'Entity'], YamlFile::get('model_properties'));
        foreach ($props as $prop => $class) {
            if (property_exists($this, $prop)) {
                $this->{$prop} = match ($prop) {
                    'queryParams' => $this->container->make($class, [
                        'tableSchema' => $this->tableSchema,
                    ]),
                    'repository' => $this->container->make($class, [
                        'crudIdentifier' => 'crudIdentifier',
                        'tableSchema' => $this->tableSchema,
                        'tableSchemaID' => $this->tableSchemaID,
                        'entity' => $this->entity,
                    ])->create(),
                    'validator' => $this->container->make($class, [
                        'validator' => YamlFile::get('validator'),
                    ]),
                    default => $this->container->make($class)
                };
            }
        }
    }
}