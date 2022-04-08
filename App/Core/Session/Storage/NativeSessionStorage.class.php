<?php

declare(strict_types=1);

class NativeSessionStorage extends AbstractSessionStorage
{
    public function __construct(GlobalVariablesInterface $gv, array $options)
    {
        parent::__construct($options, $gv);
    }

    /**
     *@inheritDoc
     */
    public function setSession(string $key, mixed $value) :void
    {
        $_SESSION[$key] = $value;
    }

    /**
     *@inheritDoc
     */
    public function setArraySession(string $key, $value) :void
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getSession(string $key, mixed $default = null) : mixed
    {
        if ($this->SessionExists($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function deleteSession(string $key) :void
    {
        if ($this->SessionExists($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @inheritDoc
     */
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

    /**
     * Invalidate a session
     *=====================================================================.
     * @return void
     */
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