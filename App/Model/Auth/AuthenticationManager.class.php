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

    public function login(bool|string $remember_me = false)
    {
        if (!$this->isLoggedInUser()) {
            $this->assign((array) $this);
            /** @var VisitorsManager */
            $visitor = $this->container->make(VisitorsManager::class)->manageVisitors([
                'ip' => H_visitors::getIP(),
            ]);
            $session_token = $this->getSessionToken();
            $rem_cookie = $this->rememberME($remember_me);
            $this->userSession->assign([
                'remember_me_cookie' => $rem_cookie,
                'session_token' => $session_token,
                'user_cookie' => $visitor->count() > 0 ? $visitor->coo : '',
                'user_agent' => $this->session->uagent_no_version(),
                'userID' => $this->userID,
                'email' => $this->email,
            ])->save();
            $this->session->set(CURRENT_USER_SESSION_NAME, $this->email);
        }
    }

    private function rememberME(bool|string $remember_me) : string
    {
        return '';
    }

    private function getSessionToken() : string
    {
        if (!$this->cookie->exists(TOKEN_NAME)) {
            $session_token = $this->userSession->getUniqueId('session_token');
            $this->cookie->set($session_token, TOKEN_NAME);
            return $session_token;
        }
        return $this->cookie->get(TOKEN_NAME);
    }

    private function isLoggedInUser(?string $id = null) : bool
    {
        if ($this->cookie->exists(TOKEN_NAME)) {
            $query_params = $this->userSession->table()->where([
                'session_token' => $this->cookie->get(TOKEN_NAME),
                'userID' => null == $id ? $this->userID : $id,
            ]);
            $this->userSession->getAll($query_params);
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