<?php

declare(strict_types=1);

class Rooter implements RooterInterface
{
    protected array $routes = [];
    protected array $params = [];
    protected string $controllerSuffix = 'Controller';
    private Container $container;

    public function __construct(private RooterHelper $rooterHelper)
    {
        $this->container = Container::getInstance();
        $this->helper = $rooterHelper;
    }

    /** @inheritDoc */
    public function add(string $route, array $params): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }

    /** @inheritDoc */
    public function dispatch(string $url): void
    {
        [$this->params,$match] = $this->helper->resolve($url, $this->routes);
        if ($match) {
            $controllerString = $this->helper->transformCtrlToCmCase($this->params['controller']) . $this->controllerSuffix;
            if (class_exists($controllerString)) {
                $method = $this->helper->transformCmCase($this->params['method']);
                $controllerObject = $this->controllerObject($controllerString, $method);
                if (\is_callable([$controllerObject, $method], true, $callableName)) {
                    $controllerObject->$method();
                } else {
                    throw new RooterBadMethodCallException('No method existe or not callable', 1);
                }
            } else {
                throw new Exception('No class exists', 1);
            }
        } else {
            throw new Exception('Page not found', 1);
        }
    }

    public function controllerObject(string $controllerString, string $method) : Controller
    {
        $controllerObject = $this->container->make($controllerString)->iniParams($controllerString, $method, $this->params, $this->getPath($controllerString));
        $this->container->bind($controllerString, fn () => $controllerObject);
        $this->container->bind($method, fn () => $method);
        return $controllerObject;
    }

    public function getPath(string $controller) : string
    {
        $controlerFile = YamlFile::get('controller');
        switch ($controller) {
                case in_array($controller, $controlerFile['backend']):
                    $path = 'Backend' . DS;
                break;
                case in_array($controller, $controlerFile['ajax']):
                    $path = 'Ajax' . DS;
                break;
                case in_array($controller, $controlerFile['auth']):
                    $path = 'Auth' . DS;
                break;
                case in_array($controller, $controlerFile['asset']):
                    $path = 'Asset' . DS;
                break;
                default:
                $path = 'Client' . DS;
                break;
            }
        $this->container->bind('ControllerPath', fn () => $path);
        return $path;
    }
}
