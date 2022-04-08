<?php

declare(strict_types=1);

abstract class AbstractModel implements ModelInterface
{
    protected QueryParams $queryParams;

    /*
     * Prevent Deleting Ids
     * ------------------------------------------------------------.
     * @return array
     */
    abstract public function guardedID() : array;

    public function table(?string $tbl = null, mixed $columns = null) : QueryParams
    {
        return $this->getQueryParams()->table($tbl, $columns);
    }

    public function getQueryParams() : QueryParams
    {
        return $this->queryParams;
    }

    public function conditions() : self
    {
        if (!$this->queryParams->hasConditions()) {
            $colID = $this->entity->getColId();
            $this->table()->where([$colID => $this->entity->{'get' . $colID}()])->build();
        }
        return $this;
    }

    /*
     * Global Before Save
     * ================================================================.
     * @return void
     */
    public function beforeSave() : mixed
    {
        if (isset(AuthManager::$currentLoggedInUser->userID) && property_exists($this, 'userID')) {
            if (!isset($this->userID) || empty($this->userID) || $this->userID == null) {
                $this->userID = AuthManager::$currentLoggedInUser->userID;
            }
        }
        if (isset($this->msg)) {
            unset($this->msg);
        }
        if (isset($this->fileErr)) {
            unset($this->fileErr);
        }
        return true;
    }

    public function beforeSaveUpadate(Entity $entity) : Entity
    {
        $f = $entity; //fields;
        // $current = new DateTime();
        // $key = current(array_filter(array_keys($fields), function ($field) {
        //     return str_starts_with($field, 'update');
        // }));
        // if ($key && !is_array($key)) {
        //     $f[$key] = $current->format('Y-m-d H:i:s');
        // }
        // if (isset($f[$this->get_colID()])) {
        //     unset($f[$this->get_colID()]);
        // }
        return $f;
    }

    public function beforeSaveInsert(Entity $entity)
    {
        return $entity;
    }

    public function afterSave(array $params = [])
    {
        return $params['saveID'] ?? null;
    }

    //Before delete
    public function beforeDelete($params = [])
    {
        return empty($params) ? true : $params;
    }

    //After delete
    public function afterDelete($params = [])
    {
        $params = null;
        return true;
    }

    /*
     * Get Col ID or TablschemaID.
     *
     * @return string
     */
    public function get_colID() : string
    {
        return isset($this->_colID) ? $this->_colID : '';
    }

    public function runValidation($validator)
    {
        $validator->run();
        $key = $validator->field;
        //dd($validator);
        if (!$validator->success) {
            $this->validates = false;
            $this->validationErr[$key] = $validator->msg;
        }
        //dd($validator);
    }

    /**
     * Get Container.
     *
     * @return ContainerInterface
     */
    public function get_container() : ContainerInterface
    {
        return $this->container;
    }
}