<?php

declare(strict_types=1);

class PdoSessionStorage extends AbstractSessionStorage
{
    /**
     * Main class constructor.
     *
     * @param object $sessionEnvironment
     */
    public function __construct(GlobalVariablesInterface $gv, array $options)
    {
        parent::__construct($options);
        $this->gv = $gv;
        $this->options = $options;
    }

    public function setSession(string $key, mixed $value): void
    {
        // TODO: Implement setSession() method.
    }

    public function setArraySession(string $key, mixed $value): void
    {
        // TODO: Implement setArraySession() method.
    }

    public function getSession(string $key, mixed $default = null)
    {
        // TODO: Implement getSession() method.
    }

    public function deleteSession(string $key): void
    {
        // TODO: Implement deleteSession() method.
    }

    public function invalidate(): void
    {
        // TODO: Implement invalidate() method.
    }

    public function SessionExists(string $key) :bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     *@inheritDoc
     */
    public function flushSession(string $key, $default) : mixed
    {
        if ($this->SessionExists($key)) {
            $value = $_SESSION[$key];
            $this->deleteSession($key);
            return $value;
        }
        return $default;
    }

    public function invalidateSession() : void
    {
        $_SESSION = [];
        if (ini_set('session.use_cookies', $this->options['use_cookies'])) {
            $params = session_get_cookie_params();
            setcookie($this->getSessionName(), '', time() - $params['lifetime'], $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_unset();
        session_destroy();
    }
}