<?php

declare(strict_types=1);
class UserSessionsManager extends Model
{
    protected $_colID = 'usID';
    protected $_table = 'user_sessions';
    protected $_colIndex = 'userID';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function save(?Entity $entity = null): ?object
    {
        $entity != null ? $this->entity = $entity : '';
        // if (( new ReflectionProperty($this->entity, $this->entity->getColId()))->isInitialized($this->entity)) {
        //     $this->update();
        // }
        return parent::save();
    }
}