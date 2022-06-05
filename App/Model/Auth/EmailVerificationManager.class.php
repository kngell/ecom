<?php

declare(strict_types=1);

class EmailVerificationManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private string $_matchingTestColumn = 'password';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID, $this->_matchingTestColumn);
    }

    public function getUser() : ?self
    {
        $this->table(null, ['userID', 'firstName', 'email', 'verified'])
            ->leftJoin('users_requests', ['COUNT|userID|number'])
            ->on(['userID', 'userID'], ['type' => [0, 'users_requests']], ['timestamp|>=|' => [time() - 60 * 60 * 24, 'users_requests']])
            ->where(['email' => [$this->entity->{'getEmail'}(), 'users']])
            ->groupBy(['userID' => 'users'])
            ->return('class');
        $user = $this->getAll();
        if ($user->count() > 0) {
            return $user->assign((array) current($user->get_results()));
        }
        return null;
    }

    public function validateAccount()
    {
    }
}