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
    public function resolve() : self;

    public function setRequest(RequestHandler $request) : self;

    public function setResponse(ResponseHandler $response) : self;

    public function setNewRouter(string $newRouter) : self;

    public function setRouteHandler(string $routeHandler) : self;

    public function setContainer(ContainerInterface $container) : self;

    public function setControllerAry(array $ctrlAry) : self;
}