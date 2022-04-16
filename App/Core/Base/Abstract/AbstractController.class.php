<?php

declare(strict_types=1);

abstract class AbstractController implements ControllerInterface
{
    protected array $middlewares = [];
    protected ContainerInterface $container;

    public function registerMiddleware(BaseMiddleWare $middleware) : void
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares() : array
    {
        return $this->middlewares;
    }

    public function resetView() : self
    {
        $this->view_instance->reset();
        return $this;
    }

    public function siteTitle(?string $title = null) : ViewInterface
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->siteTitle($title);
    }

    public function pageTitle(?string $page = null)
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->pageTitle($page);
    }

    public function getView() : ViewInterface
    {
        if (!isset($this->view_instance)) {
            $this->filePath = !isset($this->filePath) ? $this->container->make('ControllerPath') : $this->filePath;
            return  $this->view_instance->initParams($this->filePath);
        }
        return $this->view_instance;
    }

    public function get_container() : ContainerInterface
    {
        return $this->container;
    }
}