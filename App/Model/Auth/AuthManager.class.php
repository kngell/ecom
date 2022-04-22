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

    public function getUserRequests($email = '', $type = 0, $tt = 0)
    {
        $day_ago = $tt ? $tt : time() - 60 * 60 * 24;
        $tables = ['table_join' => ['users' => ['userID', 'firstName', 'lastName', 'verified'], 'users_requests' => 'COUNT|urID']];
        $data = [
            'join' => 'LEFT JOIN',
            'rel' => [['userID', 'userID'], 'params' => ['type|=' . $type . '|users_requests', 'timestamp| >=' . $day_ago . '|users_requests']],
            'where' => ['email' => ['value' => $email, 'tbl' => 'users']],
            'group_by' => ['userID' => ['tbl' => 'users']],
            'return_mode' => 'class',
        ];
        $user = $this->getAll($data, $tables);
        if ($user->count() > 0) {
            $u = current($user->get_results());
            $u->_count = $user->count();
            $u->name = $u->firstName . ' ' . $u->lastName;
            $user = null;

            return [$u, (int) $u->Number];
        }

        return false;
    }

    public function loginAttemps(string $email) : array
    {
        $query_params = $this->table()
            ->leftJoin('login_attempts', ['laID|COUNT|number'])->on(['userID', 'users' => 'userID'], ['timestamp|>=|' => [time() - 60 * 60, 'login_attempts']])
            ->where(['email' => [$email, 'users']])
            ->groupBy(['users' => 'userID'])
            ->return('class')
            ->build();
        $user = $this->getAll($query_params);
        if ($user->count() > 0) {
            $u = current($user->get_results());
            $u->_count = $user->count();
            $u->name = $u->firstName . ' ' . $u->lastName;
            $user = null;
            return [$u, (int) $u->number];
        }
        return false;
    }

    //=======================================================================
    //Login
    //=======================================================================
    public function login($rememberMe = false)
    {
        try {
            $this->id = $this->userID;
            $this->session->set($this->_sessionName, $this->email);
            self::$currentLoggedInUser = $this;
            if ($rememberMe) {
                $user_session = $this->container->make(UserSessionsManager::class)->getDetails($this->userID, 'userID');
                $session = $user_session->set_userSession($this);
                if (!$this->cookie->exists($this->_cookieName)) {
                    if (empty($this->remember_cookie) || !isset($this->remenber_cookie)) {
                        $this->remember_cookie = !is_bool($session) ? $session->remember_cookie : $this->getUniqueId('token');
                        $this->save();
                    }
                    $this->cookie->set($this->_cookieName, $this->remember_cookie, COOKIE_EXPIRY);
                } else {
                    if ($this->remember_cookie != $this->cookie->get($this->_cookieName)) {
                        $this->remember_cookie = $this->cookie->get($this->_cookieName);
                        $this->save();
                    }
                }
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    //=======================================================================
    //Logout
    //=======================================================================
    public function logout()
    {
        try {
            //check visitor Cookies
            if (!$this->cookie->exists(VISITOR_COOKIE_NAME)) {
                $this->container->make(VisitorsManager::class)->add_new_visitor();
            }
            //Delete Session
            $this->session->delete(CURRENT_USER_SESSION_NAME);
            $this->session->delete(CHECKOUT_PROCESS_NAME);
            $this->session->delete(CONTAINER_NAME);
            session_destroy();
            // $this->session->invalidate();
            self::$currentLoggedInUser = null;

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    //=======================================================================
    //Delete User Account
    //=======================================================================
    public function deleteUserAccount($user = null)
    {
        try {
            $this->container->make(UsersRequestsManager::class)->delete('', ['userID' => $user->userID]);
            $this->container->make(LoginAttemptsManager::class)->delete('', ['userID' => $user->userID]);
            $this->container->make(UserSessionsManager::class)->delete('', ['userID' => $user->userID]);
            $this->container->make(UserExtraDataManager::class)->delete('', ['userID' => $user->userID]);
            $this->container->make(AddressBookManager::class)->delete('', ['relID' => $user->userID]);
            $this->container->make(GroupUserManager::class)->delete('', ['userID' => $user->userID]);
            if ($this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
                $this->cookie->delete(REMEMBER_ME_COOKIE_NAME);
            }
            $this->session->delete(CURRENT_USER_SESSION_NAME);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // check if user is logged
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    //find Password
    public function findPassword()
    {
        $data = [
            'where' => ['userID' => $this->userID], 'return_type' => 'single', 'select' => 'password',
        ];

        return $this->findFirst($data);
    }

    public static function currentUser()
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            $id = $session->get(CURRENT_USER_SESSION_NAME);
            if (self::$currentLoggedInUser === null) {
                self::$currentLoggedInUser = Container::getInstance()->make(self::class, [
                    'user' => (int) $id,
                ]);
            }
        }
        return self::$currentLoggedInUser;
    }

    //=======================================================================
    //Check user session
    //=======================================================================
    public static function check_UserSession($params = [])
    {
        $session = GlobalManager::get('global_session');
        if (isset($params['userID'])) {
            (self::$currentLoggedInUser->userID == $params['userID'] && self::$currentLoggedInUser->email != $params['email']) ? $session->set(CURRENT_USER_SESSION_NAME, $params['email']) : '';
        }
    }

    //=======================================================================
    //Delete and restore users
    //=======================================================================
    public function deleteUser($id, $complete)
    {
        !$complete ? $this->_softDelete = true : '';

        return $this->delete('UserId', $id);
    }

    //=======================================================================
    //Restore users
    //=======================================================================
    public function restoreUser($id)
    {
        $fields = ['deleted' => 0];

        return $this->update(['userID' => $id], $fields);
    }

    //=======================================================================
    //Register
    //=======================================================================
    public function register()
    {
        if (!$this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $v_cookie = $this->getUniqueId('user_cookie');
            $this->cookie->set(VISITOR_COOKIE_NAME, $v_cookie, COOKIE_EXPIRY);
            $this->user_cookie = $v_cookie;
        }
        $this->user_cookie = $this->cookie->get(VISITOR_COOKIE_NAME);

        return $this->save();
    }

    //Check for remember me cookies
    public function rememberMe_checker()
    {
        $user_data = [];
        // if ($this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
        //     $rem = $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
        //     $user_session = $this->container->make(UserSessionsManager::class)->getDetails($rem, 'remember_cookie');
        //     if ($user_session && $user_session->count() === 1) {
        //         $user_session = current($user_session->get_results());
        //         $user_data['remember'] = true;
        //         $user_data['email'] = $user_session->email ?? '';
        //     }
        // }

        return $user_data;
    }

    //login From Facebook
    public static function loginFromFacebook($userData)
    {
        $user = new self($userData['email']);
        if (!$user->getDetails($userData['email'], 'email')) {
            $user->firstName = $userData['first_name'];
            $user->lastName = $userData['last_name'];
            $user->email = $userData['email'];
            $user->fb_access_token = $userData['accessToken'];
            $user->profileImage = $userData['picture']['url'];
            $user->save();
            $subject = 'Email verification';
            $body = '<h3>Cliquez sur le lien ci-dessous pour changer pour vérifier votre email</h3>.<p><a href="' . URLROOT . 'users/emailVerified/' . $userData['email'] . '">' . URLROOT . 'users/emailVerified/' . $userData['email'] . '</a><br>KnGELL! </p><p>Vous disposez de 30 minutes pour changer votre mot de pass. Au delà, vous devrez recommencer</p>';
            // H_Email::sendEmail($userData['email'], $subject, $body, $body);
            $user->login();
        } else {
            $user->login();
        }
    }

    /**
     * ACL Permissions fromdataBase
     * ==================================================================================================.
     * @return void
     */
    public function acls()
    {
        return $this->container->make(UsersManager::class)->get_selectedOptions($this) ?? [];
    }
    //=======================================================================
    //Getters
    //=======================================================================

    public function displayName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function get_columnNames()
    {
        return $this->_columnNames;
    }

    public function getConfirm()
    {
        return $this->_confirm;
    }

    //=======================================================================
    //Setters
    //=======================================================================
    public function setConfirm($value)
    {
        $this->_confirm = $value;
    }

    //=======================================================================
    //Operations
    //=======================================================================

    //Before Save
    public function beforeSave($params = []) : bool
    {
        if (property_exists($this, 'cpassword')) {
            $this->cpassword = null;
        }
        if (property_exists($this, 'terms')) {
            $this->terms = null;
        }

        // if ($this->isNew() == true) {
        //     $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        //     $this->salt = $this->getUniqueId('salt');
        // }
        //Unset Auth providers ???
        unset($this->oauth_provider,$this->oauth_uid,$this->link);
        if (isset($this->Number)) {
            $this->Number = null;
        }
        if (isset($this->name)) {
            unset($this->name);
        }

        return true;
    }

    //confirm Email
    public function confirmEmail($email)
    {
        $cond = ['email' => $email];

        return ($this->update($cond, ['verified' => 1]))->count();
    }

    //find user when resetting password
    public function find_from_reset_password($email, $token)
    {
        $conditions = [
            'where' => [
                'token' => !(null),
                'token' => $token,
                'deleted' => !1,
                'email' => $email,
            ],
            'return_mode' => 'class',
            'return_type' => 'single',
        ];
        $row = $this->getAll($conditions);

        return $row;
    }

    //Before update
    public function beforeSaveUpadate(Entity $entity) : Entity
    {
        return parent::beforeSaveUpadate($entity);
    }
}