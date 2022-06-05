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

    public function getRequest(int $id) : self
    {
        $request_params = $this->table()->where([$this->entity->getColID() => $id, 'type' => 0])->return('class');
        return $this->getAll($request_params);
    }
}