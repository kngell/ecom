<?php

declare(strict_types=1);

interface SessionStorageInterface
{
    /**
     * Set Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSession(string $key, mixed $value) :void;

    /**
     * Set ArraySession
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setArraySession(string $key, mixed $value) :void;

    /**
     * Get Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSession(string $key, mixed $default = null) : mixed;

    /**
     * Delete Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return bool
     */
    public function deleteSession(string $key) :void;

    /**
     * Check for exists Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return bool
     */
    public function SessionExists(string $key) :bool;

    /**
     * FlusjSession
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function flushSession(string $key, mixed $default) : mixed;

    /**
     * Invalidate Session
     * --------------------------------------------------------------------------------------------------.
     * @return void
     */
    public function invalidateSession() : void;
}