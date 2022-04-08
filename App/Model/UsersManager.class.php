<?php

declare(strict_types=1);
class UsersManager extends Model
{
    protected $_colID = 'userID';
    protected $_table = 'users';
    protected $_colIndex = '';
    protected $_colContent = '';
    protected $_media_img = 'profileImage';
    protected $_modelName;

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table))) . 'Manager';
    }
}