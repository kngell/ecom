<?php

declare(strict_types=1);
class GroupsMaganer extends Model
{
    protected $_colID = 'grID';
    protected $_table = 'groups';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}