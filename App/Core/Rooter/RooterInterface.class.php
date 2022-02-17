<?php

declare(strict_types=1);
interface RooterInterface
{
    /**
     * Add Route
     * ======================================================.
     *
     * @param string $route
     * @param string $method
     * @param array $params
     * @return void
     */
    public function add(string $method, string $route, array $params) : void;

    /**
     * Resolve
     * ======================================================.
     * @return void
     */
    public function resolve() : void;

    public function setRequest(RequestHandler $request) : self;
}