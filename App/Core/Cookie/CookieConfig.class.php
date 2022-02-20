<?php

declare(strict_types=1);

class CookieConfig
{
    /** @return void */
    public function __construct()
    {
    }

    /**
     * Main cookie configuration default array settings.
     *
     * @return array
     */
    public function baseConfig(): array
    {
        return [

            'name' => '__magmacore_cookie__',
            'expires' => 3600,
            'path' => '/',
            'domain' => 'localhost',
            'secure' => false,
            'httponly' => true,

        ];
    }
}