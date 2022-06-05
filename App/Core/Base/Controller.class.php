<?php

declare(strict_types=1);

class Controller extends AbstractController
{
    use ControllerTrait;

    protected Token $token;
    protected RequestHandler $request;
    protected ResponseHandler $response;
    protected ControllerHelper $helper;
    protected SessionInterface $session;
    protected CookieInterface $cookie;
    protected CacheInterface $cache;
    protected EventDispatcherInterface $dispatcher;
    protected CommentsInterface $comment;
    protected array $customProperties = [];

    public function __construct(array $params = [])
    {
        $this->properties($params);
    }

    /** @inheritDoc */
    public function __call($name, $argument) : void
    {
        if (is_string($name) && $name !== '') {
            $method = $name . 'Page';
            if (method_exists($this, $method)) {
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], $argument);
                    $this->after();
                }
            } else {
                throw new BaseBadMethodCallException("Method {$method} does not exists.");
            }
        } else {
            throw new Exception;
        }
    }

    public function render(string $viewName, array $context = []) : ?string
    {
        $this->throwViewException();
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->render($viewName, array_merge($this->AuthenticationFroms(), $this->searchBox(), $this->outputComments(), $context));
    }

    public function model(string $modelString) : Model
    {
        return $this->container(ModelFactory::class)->create($modelString);
    }

    public function createView() : void
    {
        if (!isset($this->view_instance)) {
            $this->view_instance = $this->container(View::class, [
                'viewAry' => [
                    'token' => $this->token,
                    'file_path' => $this->filePath,
                    'response' => $this->response,
                ],
            ]);
        }
    }

    protected function before() : void
    {
        $this->container(Middleware::class)->middlewares(middlewares: $this->callBeforeMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function after() : void
    {
        $this->container(Middleware::class)->middlewares(middlewares: $this->callAfterMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function brand() : int
    {
        switch ($this->controller) {
            case 'ClothingController':
                return 3;
                break;

            default:
                return 2;
                break;
        }
    }

    protected function jsonResponse(array $resp) : void
    {
        $this->response->jsonResponse($resp);
    }

    protected function getController() : string
    {
        return $this->controller;
    }

    protected function getMethod() : string
    {
        return $this->method;
    }

    protected function redirect(string $url, bool $replace = true, int $responseCode = 303)
    {
        // $this->redirect = new BaseRedirect($url, $this->routeParams, $replace, $responseCode);
        if ($this->redirect) {
            $this->redirect->redirect();
        }
    }

    protected function getRoutes(): array
    {
        return $this->routeParams;
    }

    protected function getSiteUrl(?string $path = null): string
    {
        return sprintf('%s://%s%s', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], ($path !== null) ? $path : $_SERVER['REQUEST_URI']);
    }

    protected function getCache() : CacheInterface
    {
        return $this->cache;
    }

    protected function getSession(): object
    {
        return SessionTrait::sessionFromGlobal();
    }

    private function throwViewException(): void
    {
        if (null === $this->view_instance) {
            throw new BaseLogicException('You can not use the render method if the build in template engine is not available.');
        }
    }
}