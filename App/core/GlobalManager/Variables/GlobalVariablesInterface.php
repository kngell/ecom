<?php

declare(strict_types=1);

interface GlobalVariablesInterface
{
    /**
     * Get GET http Method
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return mixed
     */
    public function getGet(?string $key = null) : mixed;

    /**
     * Get Post HTTP Method
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return mixed
     */
    public function getPost(?string $key = null) : mixed;

    /**
     * Get Cookies
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return mixed
     */
    public function getCookie(?string $key = null) : mixed;

    /**
     * Get Server
     * --------------------------------------------------------------------------------------------------.
     * @param string $key
     * @return mixed
     */
    public function getServer(?string $key = null) : mixed;
}
