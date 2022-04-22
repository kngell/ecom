<?php

declare(strict_types=1);
class GroupUserManager extends Model
{
    protected $_colID = 'gruID';
    protected $_table = 'group_user';
    protected $_colIndex = 'userID';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}