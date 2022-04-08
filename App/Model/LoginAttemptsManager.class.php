<?php

declare(strict_types=1);
class LoginAttemptsManager extends Model
{
    protected $_colID = 'laID';
    protected $_table = 'login_attempts';
    protected $_colIndex = 'userID';
    protected $_modelName;

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table))) . 'Manager';
    }
}