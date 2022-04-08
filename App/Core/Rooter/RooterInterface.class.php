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
     * @return self
     */
    public function resolve(string $url) : self;

    public function setProperties(array $params = []) : self;
}