<?php

declare(strict_types=1);

abstract class AbstractCookieStore implements CookieStoreInterface
{
    /** @var CookieEnvironment */
    protected CookieEnvironment $cookieEnvironment;

    /**
     * Main class constructor.
     *
     * @param CookieEnvironment $cookieEnvironment
     */
    public function __construct(CookieEnvironment $cookieEnvironment)
    {
        $this->cookieEnvironment = $cookieEnvironment;
    }
}