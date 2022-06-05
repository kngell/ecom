<?php

declare(strict_types=1);

abstract class AbstractController
{
    protected array $middlewares = [];
    protected View $view_instance;
    /** @var array */
    protected array $callBeforeMiddlewares = [];
    /** @var array */
    protected array $callAfterMiddlewares = [];
    protected string $filePath;

    public function getComment() : CommentsInterface
    {
        return $this->comment;
    }

    /**
     * Get the value of filePath.
     */
    public function getFilePath() : string
    {
        return $this->filePath;
    }

    public function view() : View
    {
        return $this->view_instance;
    }

    /**
     * Set the value of filePath.
     *
     * @return  self
     */
    public function setFilePath(string $filePath) : self
    {
        $this->filePath = $filePath;
        return $this;
    }

    protected function registerMiddleware(BaseMiddleWare $middleware) : void
    {
        $this->middlewares[] = $middleware;
    }

    protected function registerBeforeMiddleware(array $middlewares = []) : void
    {
        foreach ($middlewares as $name => $middleware) {
            $this->callBeforeMiddlewares[$name] = $middleware;
        }
    }

    protected function getMiddlewares() : array
    {
        return $this->middlewares;
    }

    protected function callAfterMiddlewares(): array
    {
        return $this->callAfterMiddlewares;
    }

    protected function callBeforeMiddlewares(): array
    {
        return array_merge($this->defineCoreMiddeware(), $this->callBeforeMiddlewares);
    }

    protected function defineCoreMiddeware(): array
    {
        return [
            'Error404' => Error404::class,
            'ShowCommentsMiddlewares' => ShowCommentsMiddlewares::class,
            'SelectPathMiddleware' => SelectPathMiddleware::class,
        ];
    }

    protected function setLayout(string $layout) : self
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('View doest not exist !');
        }
        $this->view_instance->layout($layout);
        return $this;
    }

    protected function pageTitle(?string $page = null)
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->pageTitle($page);
    }

    protected function getView() : View
    {
        if (!isset($this->view_instance)) {
            $this->filePath = !isset($this->filePath) ? $this->container->make('ControllerPath') : $this->filePath;
            return  $this->view_instance->initParams($this->filePath);
        }
        return $this->view_instance;
    }

    protected function geContainer() : ContainerInterface
    {
        return $this->container;
    }

    protected function resetView() : self
    {
        $this->view_instance->reset();
        return $this;
    }

    protected function siteTitle(?string $title = null) : View
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->siteTitle($title);
    }
}