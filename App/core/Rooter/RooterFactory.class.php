<?php

declare(strict_types=1);
class RooterFactory
{
    /** @var string */
    protected string $dispatchedUrl;
    /** @var array */
    protected array $routes;

    /**
     * Main constructor.
     */
    public function __construct(private RooterInterface $rooter)
    {
    }

    public function create(?string $dispatchedUrl, array $routes) : self
    {
        $this->dispatchedUrl = $dispatchedUrl;
        $this->routes = $routes;
        if (empty($routes)) {
            throw new BaseNoValueException("There are one or more empty arguments. In order to continue, please ensure your <code>routes.yaml</code> has your defined routes and you are passing the correct variable ie 'QUERY_STRING'");
        }
        if (!$this->rooter instanceof RooterInterface) {
            throw new BaseUnexpectedValueException(get_class($this->rooter) . ' is not a valid rooter Object!');
        }
        return $this;
    }

    /**
     * Build Routes
     * ==========================================================.
     * @return RooterInterface|null
     */
    public function buildRoutes() : ?RooterInterface
    {
        if (is_array($this->routes) && !empty($this->routes)) {
            $args = [];
            foreach ($this->routes as $key => $route) {
                if (isset($route['controller']) && $route['controller'] != '') {
                    $args = [
                        'controller' => $route['controller'],
                        'method' => $route['method'],
                    ];
                }
                if (isset($key)) {
                    $this->rooter->add($key, $args);
                }
            }
            $this->rooter->dispatch($this->dispatchedUrl);
            return $this->rooter;
        }
        return null;
    }
}