<?php

declare(strict_types=1);
class SlidersManager extends Model
{
    protected $_colID = 'slID';
    protected $_table = 'sliders';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}