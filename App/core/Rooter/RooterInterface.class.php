<?php

declare(strict_types=1);
interface RooterInterface
{
    /**
     * Add Route
     * ======================================================.
     *
     * @param string $route
     * @param array $params
     * @return void
     */
    public function add(string $route, array $params) : void;

    /**
     * Dispatch
     * ======================================================.
     * Create a controller.
     * @param string $url
     * @return void
     */
    public function dispatch(string $url) : void;
}
