<?php

declare(strict_types=1);

interface CookieInterface
{
    /**
     * Set a cookie within the domain.
     *
     * @param mixed $value
     * @return void
     */
    public function set(mixed $value) : void;

    public function get(string $name) : mixed;

    /**
     * Checks to see whether the cookie was set or not return true or false.
     * @param string $name
     * @return bool
     */
    public function exists(string $name = '') : bool;

    /**
     * delete a single cookie from the domain.
     *
     * @return void
     */
    public function delete() : void;

    /**
     * Invalid all cookie i.e delete all set cookie within this domain.
     *
     * @return void
     */
    public function invalidate() : void;
}