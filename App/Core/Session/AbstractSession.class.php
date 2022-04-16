<?php

declare(strict_types=1);
abstract class AbstractSession
{
    /** @var string */
    protected const SESSION_PATTERN = '/^[a-zA-Z0-9_\.]{1,64}$/';

    /**
     * Get User Agent client.
     *
     * @return string
     */
    public static function uagent_no_version() : string
    {
        $uagent = $_SERVER['HTTP_USER_AGENT'];
        $regx = '/\/[a-zA-z0-9.]+/';
        $newString = preg_replace($regx, '', $uagent);
        return $newString;
    }

    /**
     * Check for valid session key.
     *
     * @param string $sessionName
     * @return bool
     */
    protected function isSessionKeyValid(string $sessionName) : bool
    {
        return preg_match(self::SESSION_PATTERN, $sessionName) === 1;
    }

    protected function ensureSessionKeyIsValid(string $key) : void
    {
        if ($this->isSessionKeyValid($key) === false) {
            throw new SessionInvalidArgument($key . ' is not a valid sesion Key.');
        }
    }
}