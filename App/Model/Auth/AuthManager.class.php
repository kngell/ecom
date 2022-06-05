<?php

declare(strict_types=1);
class AuthManager extends Model
{
    public static $currentLoggedInUser = null;
    protected $_colID = 'userID';
    protected $_table = 'users';
    private $_isLoggedIn = false;
    private $_confirm;

    public function __construct(string $user = '')
    {
        parent::__construct($this->_table, $this->_colID);
        if ($user) {
            $u = is_numeric($user) ? $this->getDetails($user) : $this->getDetails($user, 'email');
            if ($u->count() > 0) {
                $this->entity->assign((array) $u->get_results());
            }
        }
    }

    public static function user() : string
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return $session->get(CURRENT_USER_SESSION_NAME)['first_name'];
        }
        return '';
    }

    public static function acls() : array
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return $session->get(CURRENT_USER_SESSION_NAME)['acl'];
        }
        return [];
    }

    // check if user is logged
    public static function isLoggedIn() : bool
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return true;
        }
        return false;
    }

    public static function currentUser()
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            $id = $session->get(CURRENT_USER_SESSION_NAME)['id'];
            if (self::$currentLoggedInUser === null) {
                self::$currentLoggedInUser = Container::getInstance()->make(self::class, [
                    'user' => (int) $id,
                ]);
            }
        }
        return self::$currentLoggedInUser;
    }
}