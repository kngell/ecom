<?php

declare(strict_types=1);

class AuthenticationManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private string $_matchingTestColumn = 'password';
    private ModelInterface $userSession;

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID, $this->_matchingTestColumn);
        $this->userSession = $this->container->make(UserSessionsManager::class);
    }

    public function authenticate()
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

    public function login(bool|string $remember_me = false) : ?ModelInterface
    {
        if (!$this->isLoggedInUser()) {
            $this->assign((array) $this);
            /** @var VisitorsManager */
            $visitor = $this->container->make(VisitorsManager::class)->manageVisitors([
                'ip' => H_visitors::getIP(),
            ]);
            $this->session->set(CURRENT_USER_SESSION_NAME, (int) $this->userID);
            return $this->userSession->assign($this->manageSession($visitor, $remember_me))->save();
        }
        return null;
    }

    private function rememberME(bool|string $remember_me) : string
    {
        if ($remember_me != false) {
            if (!$this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
                $this->userSession->getQueryParams()->reset();
                $rem_cookie = $this->userSession->getUniqueId('remember_me_cookie');
                $this->cookie->set($rem_cookie, REMEMBER_ME_COOKIE_NAME);
                return $rem_cookie;
            }
        }
        return $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
    }

    private function manageSession(ModelInterface $visitor, bool|string $remember_me) : array
    {
        if (!$this->cookie->exists(TOKEN_NAME)) {
            $this->userSession->getQueryParams()->reset();
            $session_token = $this->userSession->getUniqueId('session_token');
            $this->cookie->set($session_token, TOKEN_NAME);
        }
        return [
            'remember_me_cookie' => $this->rememberME($remember_me),
            'session_token' => $session_token ?? $this->cookie->get(TOKEN_NAME),
            'user_cookie' => $visitor->count() > 0 ? $visitor->cookies : '',
            'user_agent' => $this->session->uagent_no_version(),
            'userID' => $this->userID,
            'email' => $this->email,
        ];
    }

    private function isLoggedInUser(?string $id = null) : bool
    {
        $query_params = $this->userSession->table()->where([
            'session_token' => $this->cookie->exists(TOKEN_NAME) ? $this->cookie->get(TOKEN_NAME) : null,
            'userID' => null == $id ? $this->userID : $id,
        ]);
        $this->userSession = $this->userSession->getAll($query_params);
        if ($this->cookie->exists(TOKEN_NAME)) {
            if ($this->userSession->count() > 0) {
                if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function loginAttemps(string $email) : self
    {
        $this->table()
            ->leftJoin('login_attempts', ['laID|COUNT|number'])->on(['userID', 'users' => 'userID'], ['timestamp|>=|' => [time() - 60 * 60, 'login_attempts']])
            ->where(['email' => [$email, 'users']])
            ->groupBy(['users' => 'userID'])
            ->return('class')
            ->build();
        return $this->getAll();
    }
}