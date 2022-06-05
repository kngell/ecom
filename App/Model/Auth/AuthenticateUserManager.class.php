<?php

declare(strict_types=1);

class AuthenticateUserManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private Model $userSession;

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
        $this->userSession = $this->container->make(UserSessionsManager::class);
    }

    public function authenticate() : ?array
    {
        $user = $this->loginAttemps($this->entity->{'getEmail'}());
        if ($user->count() > 0) {
            /** @var ModelInterfce */
            $u = current($user->get_results());
            $u->_count = $user->count();
            $u->setEntity($this->entity);
            $user = null;
            return [$u, (int) $u->number];
        }
        return false;
    }

    public function login(bool|string $remember_me, array $data) : ?Model
    {
        if (!AuthManager::isLoggedIn()) {
            $this->assign((array) $this);
            /** @var VisitorsManager */
            $visitor = $this->container->make(VisitorsManager::class)->manageVisitors([
                'ip' => H_visitors::getIP(),
            ]);
            $this->session->set(CURRENT_USER_SESSION_NAME, [
                'id' => (int) $this->user_id,
                'first_name' => (string) $this->first_name,
                'last_name' => (string) $this->last_name,
                'acl' => (array) $this->acls(),
                'verified' => (int) $this->verified,
            ]);
            return $this->userSession->assign(array_merge($data, $this->manageSession($visitor, $remember_me)))->save();
        }
        return null;
    }

    public function rememberMeCheck() : array
    {
        if ($this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
            $rem = $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
            $this->userSession->getDetails($rem, 'remember_me_cookie');
            if ($this->userSession->count() === 1) {
                $userSession = $this->userSession->get_results();
                return [
                    'remember' => true,
                    'email' => $userSession->email,
                    'password' => $userSession->password,
                ];
            }
        }
        return [];
    }

    private function rememberMe(bool|string $remember_me, UserSessionsManager $session) : string
    {
        $rem_cookie = '';
        if ($remember_me != false) {
            if ($session->count() === 1) {
                if (!( new ReflectionProperty($session->getEntity(), $session->getEntity()->getFields('rememberMeCookie')))->isInitialized($session->getEntity())) {
                    return $this->rememberCookie();
                }
                return $session->getEntity()->{'getRememberMeCookie'}();
            }
            $rem_cookie = $this->rememberCookie();
        }
        return $rem_cookie;
    }

    private function rememberCookie() : string
    {
        $rem_cookie = '';
        if (!$this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
            $this->userSession->getQueryParams();
            $rem_cookie = $this->userSession->getUniqueId('rememberMeCookie');
            $this->cookie->set($rem_cookie, REMEMBER_ME_COOKIE_NAME);
        } else {
            $rem_cookie = $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
        }
        return $rem_cookie;
    }

    private function sessionToken(UserSessionsManager $session) : string
    {
        $sessionToken = '';
        if ($session->count() === 1) {
            if (!( new ReflectionProperty($session->getEntity(), $session->getEntity()->getFields('sessionToken')))->isInitialized($session->getEntity())) {
                if (!$this->cookie->exists(TOKEN_NAME)) {
                    $session->getQueryParams();
                    $sessionToken = $session->getUniqueId('sessionToken');
                    $this->cookie->set($sessionToken, TOKEN_NAME);
                } else {
                    $sessionToken = $this->cookie->get(TOKEN_NAME);
                }
            } else {
                $sessionToken = $session->getEntity()->{'getSessionToken'}();
                if (!$this->cookie->exists(TOKEN_NAME)) {
                    $this->cookie->set($sessionToken, TOKEN_NAME);
                } else {
                    if ($this->cookie->get(TOKEN_NAME) !== $sessionToken) {
                        $this->cookie->set($sessionToken, TOKEN_NAME);
                    }
                }
            }
        } else {
            if (!$this->cookie->exists(TOKEN_NAME)) {
                $session->getQueryParams();
                $sessionToken = $session->getUniqueId('sessionToken');
                $this->cookie->set($sessionToken, TOKEN_NAME);
            } else {
                $sessionToken = $this->cookie->get(TOKEN_NAME);
            }
            if ($session->count() > 1) {
                $session->getQueryParams();
                $session->table()->where(['userID' => $this->userID]);
                $session->delete();
            }
        }
        $session = null;
        return $sessionToken;
    }

    private function manageSession(Model $visitor, bool|string $remember_me) : array
    {
        $sessionDb = $this->userSession->getDetails($this->getEntity()->{'getUserId'}(), 'user_id');
        $sessionDb->count() == 1 ? $sessionDb = $sessionDb->assign((array) $sessionDb->get_results()) : '';
        return [
            'remember_me_cookie' => $this->rememberMe($remember_me, $sessionDb),
            'session_token' => $this->sessionToken($sessionDb),
            'user_cookie' => $visitor->count() > 0 ? $visitor->cookies : '',
            'user_agent' => $this->session->uagent_no_version(),
            'user_id' => $this->user_id,
            'email' => $this->email,
        ];
    }

    private function loginAttemps(string $email) : self
    {
        $this->table()
            ->leftJoin('login_attempts', ['COUNT|la_id|number'])
            ->on(['user_id', 'user_id|login_attempts'], ['timestamp|>=|' => [time() - 60 * 60, 'login_attempts']])
            ->where(['email' => [$email, 'users']])
            ->groupBy(['user_id' => 'users'])
            ->return('class')
            ->build();
        return $this->getAll();
    }

    private function acls() : array
    {
        return $this->container->make(UsersManager::class)->get_selectedOptions($this) ?? [];
    }
}