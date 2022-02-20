<?php

declare(strict_types=1);

class Rooter implements RooterInterface
{
    private string $route = '/';
    private array $arguments = [];
    private array $routes = [];
    private mixed $params;
    private string $controllerSuffix = 'Controller';
    private string $routeHandler;
    private string $newRouter;
    private Container $container;
    private RequestHandler $request;
    private ResponseHandler $response;
    private View $view;

    public function __construct(private RooterHelper $helper)
    {
        $this->container = Container::getInstance();
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

    public function get(string $path, mixed $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, mixed $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * Parse URL
     * =========================================================.
     * @return string
     */
    public function parseUrl(?string $urlroute = null) : string
    {
        $url = [];
        if (isset($urlroute) && !empty($urlroute)) {
            if ($urlroute == '/') {
                return $this->route = $urlroute;
            }
            if ($urlroute == 'favicon.ico') {
                $this->arguments = [$urlroute];

                return 'assets';
            }
            $url = explode('/', filter_var(rtrim($urlroute, '/'), FILTER_SANITIZE_URL));
            $route = isset($url[0]) ? strtolower($url[0]) : $this->route;
            unset($url[0]);
            $this->arguments = count($url) > 0 ? array_values($url) : [];

            return $route;
        }

        return $this->route;
    }

    /** @inheritDoc */
    public function resolve(): self
    {
        $url = $this->parseUrl($this->helper->formatQueryString(strtolower($this->request->getPath())));
        list($this->params, $match) = $this->getMatchRoute($url, $this->routes[$this->request->getMethod()]);
        if (!$match) {
            throw new RouterNoRoutesFound('Page not found', 1);
        }
        if (is_string($this->params)) {
            $this->view->render('', []);
        }
        $controllerString = $this->helper->transformCtrlToCmCase($this->params['controller']) . $this->controllerSuffix;
        if (class_exists($controllerString)) {
            $method = $this->helper->transformCmCase($this->params['method']);
            $controllerObject = $this->controllerObject($controllerString, $method);
            if (\is_callable([$controllerObject, $method], true, $callableName)) {
                $controllerObject->$method($this->arguments);
            } else {
                throw new NoActionFoundException('No method existe or not callable', 1);
            }
        } else {
            throw new RouterNoControllerFoundException('No controller exists', 1);
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
     * @return array
     */
    public function getMatchRoute(string $url, array $routes) : array
    {
        foreach ($routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                return [$params, true];
            }
        }
        return [[], false];
    }

    public function controllerObject(string $controllerString, string $method) : Controller
    {
        $controllerObject = $this->container->make($controllerString)->iniParams($controllerString, $method, $this->params, $this->getNamespace($controllerString));
        $this->container->bind($controllerString, fn () => $controllerObject);
        $this->container->bind($method, fn () => $method);
        return $controllerObject;
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }

    public function setRequest(RequestHandler $request) : self
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse(ResponseHandler $response) : self
    {
        $this->response = $response;
        return $this;
    }

    public function setView(View $view) : self
    {
        $this->view = $view;
        return $this;
    }

    public function setNewRouter(string $newRouter) : self
    {
        $this->newRouter = $newRouter;
        return $this;
    }

    public function setRouteHandler(string $routeHandler) : self
    {
        $this->routeHandler = $routeHandler;
        return $this;
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
}