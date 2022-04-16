<?php

declare(strict_types=1);

abstract class AbstractCookieStore implements CookieStoreInterface
{
    /** @var CookieEnvironment */
    protected CookieEnvironment $cookieEnvironment;
    protected GlobalVariablesInterface $gv;

    /**
     * Main class constructor.
     *
     * @param CookieEnvironment $cookieEnvironment
     */
    public function __construct(CookieEnvironment $cookieEnvironment, GlobalVariablesInterface $gv)
    {
        $this->cookieEnvironment = $cookieEnvironment;
        $this->gv = $gv;
    }
}