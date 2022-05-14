<?php

declare(strict_types=1);

class LogoutUserManager extends Model
{
    private string $_colID = 'userID';
    private string $_table = 'users';
    private Model $userSession;

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
        $this->userSession = $this->container->make(UserSessionsManager::class);
    }

    public function logout()
    {
        list($token, $id) = $this->deleteUserSessionAndCookie();
        if (!empty($id)) {
            $this->userSession->table()->where(['userID' => $id, 'session_token' => $token]);
            $this->userSession = $this->userSession->getAll();
            if ($this->userSession->count() === 1) {
                $this->userSession->getQueryParams()->reset();
                return $this->userSession->assign((array) current($this->userSession->get_results()))->delete();
            }
        }
        return null;
    }

    private function deleteUserSessionAndCookie() : array
    {
        if ($this->cookie->exists(TOKEN_NAME)) {
            $token = $this->cookie->get(TOKEN_NAME);
            $this->cookie->delete(TOKEN_NAME);
        }
        if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
            $id = $this->session->get(CURRENT_USER_SESSION_NAME);
            $this->session->delete(CURRENT_USER_SESSION_NAME);
        }
        return [$token ?? '', $id ?? ''];
    }
}
