<?php

declare(strict_types=1);
class FilterCommentManager extends Model
{
    protected $_colID = 'fltID';
    protected $_table = 'filter_comment';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getFilters()
    {
        $this->table()->return('object');
        return $this->getAll()->get_results();
    }
}