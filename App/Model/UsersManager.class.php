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

    public function get_selectedOptions(?Model $m = null)
    {
        /** @var Model */
        $groups = $this->container->make(GroupsMaganer::class);
        $query_params = $groups->table()->join('group_user', ['user_id', 'group_id'])
            ->on(['gr_id', 'group_id'])
            ->where(['user_id' => [$m->getEntity()->{'getUserId'}(), 'group_user']])
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