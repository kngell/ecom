<?php

declare(strict_types=1);
class LoginAttemptsManager extends Model
{
    protected $_colID = 'laID';
    protected $_table = 'login_attempts';
    protected $_colIndex = 'userID';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}