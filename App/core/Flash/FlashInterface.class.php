<?php

declare(strict_types=1);

interface FlashInterface
{
    /**
     * Add a flash message via Session
     * --------------------------------------------------------------------------------------------------.
     * @param string $msg
     * @param string $type
     * @return void
     */
    public static function add(string $msg = '', string $type = FlashTypes::SUCCESS) :void;

    /**
     * Get All massage within Session
     * --------------------------------------------------------------------------------------------------.
     * @return void
     */
    public static function get();
}
