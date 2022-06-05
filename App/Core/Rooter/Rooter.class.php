<?php

declare(strict_types=1);

class Rooter implements RooterInterface
{
    private string $route = '/';
    private array $arguments = [];
    private array $routes = [];
    private array $controllerAry = [];
    private mixed $params;
    private string $controllerSuffix = 'Controller';
    private string $methodSuffix = 'Page';
    private ContainerInterface $container;

    public function __construct(private RooterHelper $helper, private ResponseHandler $response, private RequestHandler $request, private array $controllerProperties)
    {
    }

    /** @inheritDoc */
    public function add(string $method, string $route, array $params): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$method][$route] = $params;
    }

    /**
     * Parse URL
     * =========================================================.
     * @return string|ResponseHandler
     */
    public function parseUrl(?string $urlroute = null) : string|ResponseHandler
    {
        if ($urlroute != null) {
            if ($urlroute == '') {
                $this->route = $urlroute = DS;
            } elseif ($urlroute == 'favicon.ico') {
                $this->arguments = [$urlroute];
                $this->route = $urlroute = 'assets';
            } else {
                $url = explode(DS, filter_var(rtrim($urlroute, DS), FILTER_SANITIZE_URL));
                $this->route = isset($url[0]) ? strtolower($url[0]) : $this->route;
                unset($url[0]);
                $this->arguments = count($url) > 0 ? $this->helper->formatUrlArguments(array_values($url)) : [];
            }
            return strtolower($this->route);
        }
        return DS;
    }

    /** @inheritDoc */
    public function resolve(): self
    {
        $url = $this->request->getPath();
        list($controllerString, $method) = $this->resolveWithException($url);
        $controllerObject = $this->controllerObject($controllerString, $method);
        if (preg_match('/method$/i', $method) == 0) {
            if (YamlFile::get('app')['system']['use_resolvable_method'] === true) {
                $this->resolveControllerMethodDependencies($controllerObject, $method);
            } elseif (\is_callable([$controllerObject, $method], true, $callableName)) {
                $controllerObject->$method($this->arguments);
            } else {
                throw new NoActionFoundException("Method $method in controller $controllerString cannot be called");
            }
        } else {
            throw new NoActionFoundException("Method $method in controller $controllerString cannot be called directly - remove the Action suffix to call this method");
        }
        return $this;
    }

    /**
     * Resolve
     * ==========================================================
     * Match route to routes in the rooting table and set params;.
     *
     * @param string $url
     * @param array $routes
     * @return bool
     */
    public function getMatchRoute(string $url, array $routes) : bool
    {
        foreach ($routes as $route => $params) {
            if (preg_match($route, $this->helper->dynamicNamespace($route, $this->route, $this->controllerAry) . $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function controllerObject(string $controllerString, string $method) : Controller
    {
        return $this->container->make(ControllerFactory::class, [
            'controllerString' => $controllerString,
            'method' => $method,
            'path' => $this->getNamespace($controllerString),
            'controllerProperties' => $this->controllerProperties,
        ])->create();
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Get the namespace for the controller class. the namespace difined within the route parameters
     * only if it was added.
     *
     * @param string $string
     * @return string
     */
    public function getNamespace(?string $string = null) : string
    {
        $namespace = '';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . DS;
        }
        return $namespace;
    }

    private function resolveControllerMethodDependencies(object $controllerObject, string $newAction): mixed
    {
        $newAction = $newAction . $this->methodSuffix;
        $reflectionMethod = new ReflectionMethod($controllerObject, $newAction);
        $reflectionMethod->setAccessible(true);
        if ($reflectionMethod) {
            $dependencies = [];
            foreach ($reflectionMethod->getParameters() as $param) {
                $newAction = Application::diGet(YamlFile::get('providers')[$param->getName()]);
                if (isset($newAction)) {
                    $dependencies[] = $newAction;
                } elseif ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                }
            }
            $reflectionMethod->setAccessible(false);
            return $reflectionMethod->invokeArgs($controllerObject, $dependencies);
        }
    }

    private function createController(): string
    {
        $controllerName = $this->params['controller'] . $this->controllerSuffix;
        $controllerName = Stringify::studlyCaps($controllerName);
        return $controllerName;
    }

    private function resolveWithException(string $url): array
    {
        $url = $this->parseUrl($url);
        if (!$this->getMatchRoute($url, $this->routes[$this->request->getMethod()])) {
            http_response_code(404);
            throw new RouterNoRoutesFound('Route ' . $url . ' does not match any valid route.', 404);
        }
        if (!class_exists($controller = $this->createController())) {
            throw new RouterBadFunctionCallException('Class ' . $controller . ' does not exists.');
        }
        return [$controller, $this->createMethod()];
    }

    private function createMethod(): string
    {
        $method = $this->params['method'];
        return Stringify::camelCase($method);
    }
}