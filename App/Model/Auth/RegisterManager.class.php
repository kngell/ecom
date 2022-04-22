<?php

declare(strict_types=1);

class RegisterManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private string $_matchingTestColumn = 'password';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID, $this->_matchingTestColumn);
    }
}