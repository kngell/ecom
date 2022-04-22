<?php

declare(strict_types=1);
class UsersManager extends Model
{
    protected $_colID = 'userID';
    protected $_table = 'users';
    protected $_colIndex = '';
    protected $_colContent = '';
    protected $_media_img = 'profileImage';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function get_selectedOptions(?ModelInterface $m = null)
    {
        /** @var ModelInterface */
        $groups = $this->container->make(GroupsMaganer::class);
        $query_params = $groups->table()->join('group_user', ['userID', 'groupID'])
            ->on(['grID', 'groupID'])
            ->where(['userID' => [$m->getEntity()->{'getUserID'}(), 'group_user']])
            ->return('class')
            ->build();
        $user_roles = $groups->getAll($query_params);
        $response = [];
        if ($user_roles->count() >= 1) {
            foreach ($user_roles->get_results() as $role) {
                $response[$role->groupID] = $role->name;
            }
        }
        $user_roles = null;
        return $response ? $response : [];
    }
}