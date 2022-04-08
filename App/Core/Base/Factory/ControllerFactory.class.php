<?php

declare(strict_types=1);

class ControllerFactory
{
    private ContainerInterface $container;
    private string $controllerString;
    private string $method;
    private array $params;
    private string $path;

    public function __construct(string $controllerString, string $method, array $params, string $path)
    {
        $this->controllerString = $controllerString;
        $this->method = $method;
        $this->params = $params;
        $this->path = $path;
    }

    public function create() : Controller
    {
        $controllerObject = $this->container->make($this->controllerString, [
            'params' => $this->getParams($this->controllerString, $this->method, $this->params, $this->path),
        ]);
        if (!$controllerObject instanceof Controller) {
            throw new BadControllerExeption($this->controllerString . ' is not a valid Controller');
        }
        $this->container->bind($this->controllerString, fn () => $controllerObject);
        $this->container->bind($this->method, fn () => $this->method);
        return $controllerObject;
    }

    private function getParams(string $controllerString, string $method, array $params, string $path)
    {
        return  [
            'token' => $this->container->make(Token::class),
            'money' => $this->container->make(MoneyManager::class),
            'helper' => $this->container->make(ControllerHelper::class),
            'loginFrm' => $this->container->make(LoginForm::class),
            'registerFrm' => $this->container->make(RegisterForm::class),
            'controller' => $controllerString,
            'method' => $method,
            'routeParams' => $params,
            'filePath' => $path,
            'dispatcher' => $this->container->make(DispatcherFactory::class)->create(),
            'request' => $this->container->make(RequestHandler::class),
            'response' => $this->container->make(ResponseHandler::class),
            'session' => $this->container->make(SessionInterface::class),
            'cache' => $this->container->make(CacheInterface::class),
            'cookie' => $this->container->make(CookieInterface::class),
            'container' => $this->container,
        ];
    }
}