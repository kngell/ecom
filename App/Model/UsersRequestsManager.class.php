<?php

declare(strict_types=1);
class UsersRequestsManager extends Model
{
    protected $_colID = 'urID';
    protected $_table = 'users_requests';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}