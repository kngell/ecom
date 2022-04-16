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
        $en = $entity != null ? $entity : $this->entity;
        $this->delete($en->{$en->getColId()});
        return parent::save();
    }
}