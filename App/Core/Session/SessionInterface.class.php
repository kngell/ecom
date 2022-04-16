<?php

declare(strict_types=1);

interface SessionInterface
{
    /**
     * Set Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function set(string $key, $value) :void;

    /**
     * Set Array Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function setArray(string $key, $value) :void;

    /**
     * Get Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param [type] $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null) : mixed;

    /**
     * Delete Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return bool
     */
    public function delete(string $key) :bool;

    /**
     * Invalidate a Session
     * --------------------------------------------------------------------------------------------------.
     * @return void
     */
    public function invalidate() :void;

    /**
     * Flush Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function flush(string $key, $value = null) : void;

    /**
     * Check if Session exists
     *--------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return bool
     */
    public function exists(string $key) : bool;

    public static function uagent_no_version() : string;
}