<?php

declare(strict_types=1);

abstract class AbstractSessionStorage implements SessionStorageInterface
{
    protected array $options = [];
    protected GlobalVariables $gv;

    /**
     * init options for session
     * =====================================================================.
     * @param array $options
     */
    public function __construct(array $options, GlobalVariablesInterface $gv)
    {
        $this->gv = $gv;
        $this->options = $options;
        $this->iniSet();
        if ($this->isSessionStarted()) {
            session_unset();
            session_destroy();
        }
        $this->start();
        return $this;
    }

    /**
     * Set Session Name
     * =====================================================================.
     * @param string $sessionName
     * @return void
     */
    public function setSessionName(string $sessionName) :void
    {
        session_name($sessionName);
    }

    /**
     * Get Session Name
     * =====================================================================.
     * @return string
     */
    public function getSessionName() : string
    {
        return session_name();
    }

    /**
     * Set Session ID
     * =====================================================================.
     * @param string $sessionID
     * @return void
     */
    public function setSessionID(string $sessionID) :void
    {
        session_id($sessionID);
    }

    /**
     * Get Session ID
     * =====================================================================.
     * @return void
     */
    public function getSessionID()
    {
        return session_id();
    }

    /**
     * Ini Set params
     * =====================================================================.
     * @return void
     */
    public function iniSet()
    {
        ini_set('session.gc_maxlifetime', $this->options['gc_maxlifetime']);
        ini_set('session.gc_divisor', $this->options['gc_divisor']);
        ini_set('session.gc_probability', $this->options['gc_probability']);
        ini_set('session.cookie_lifetime', $this->options['cookie_lifetime']);
        ini_set('session.use_cookies', $this->options['use_cookies']);
    }

    /**
     * Check if Session started
     * =====================================================================.
     * @return bool
     */
    public function isSessionStarted()
    {
        return php_sapi_name() != 'cli' ? $this->getSessionID() !== '' : false;
    }

    /**
     * Start PHP Session
     * =====================================================================.
     * @return void
     */
    public function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_save_path(ROOT_DIR . DS . 'session_dir');
            session_start();
        }
    }

    /**
     * Start session with and set params
     *=====================================================================.
     * @return void
     */
    public function start()
    {
        $this->setSessionName($this->options['session_name']);
        $serverName = $this->gv->getServer('SERVER_NAME');
        $serverHttps = $this->gv->getServer('HTTPS');
        $domain = isset($this->options['domain']) ? $this->options['domain'] : isset($serverName);
        $sercure = isset($this->options['secure']) ? $this->options['secure'] : isset($serverHttps);
        session_set_cookie_params($this->options['lifetime'], $this->options['path'], $domain, $sercure, $this->options['http_only']);
        $this->startSession();
    }
}