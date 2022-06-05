<?php

declare(strict_types=1);

class RegisterUserManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private string $_matchingTestColumn = 'password';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID, $this->_matchingTestColumn);
    }

    public function register() : ?self
    {
        try {
            $this->getQueryParams()->reset();
            /** @var UsersEntity */
            $entity = $this->getEntity()->assign([
                'salt' => $this->getUniqueId('salt'),
                'userCookie' => $this->cookie->get(VISITOR_COOKIE_NAME),
            ]);
            $entity->setPassword(password_hash($entity->getPassword(), PASSWORD_DEFAULT));
            $entity->delete('terms')->delete('cpassword');
            $save = $this->save($entity);
            return $save;
        } catch (\Throwable $th) {
            throw new BaseResourceNotFoundException($th->getMessage(), $th->getCode());
        }
    }
}