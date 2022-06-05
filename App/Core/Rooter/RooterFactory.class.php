<?php

declare(strict_types=1);
class RooterFactory
{
    /** @var array */
    private array $routes;

    /**
     * Main constructor.
     */
    public function __construct(private RooterInterface $rooter, array $routes)
    {
        $this->routes = $routes;
    }

    public function create() : RooterInterface
    {
        if (empty($this->routes)) {
            throw new BaseNoValueException("There are one or more empty arguments. In order to continue, please ensure your <code>routes.yaml</code> has your defined routes and you are passing the correct variable ie 'QUERY_STRING'");
        }
        if (!$this->rooter instanceof RooterInterface) {
            throw new BaseUnexpectedValueException(get_class($this->rooter) . ' is not a valid rooter Object!');
        }
        return $this->buildRoutes();
    }

    /**
     * Building Routes
     * =========================================================.
     * @return RooterInterface|null
     */
    public function buildRoutes() : ?RooterInterface
    {
        if (count($this->routes) > 0) {
            if (is_array($this->routes) && !empty($this->routes)) {
                foreach ($this->routes as $mthd => $routes) {
                    $args = [];
                    foreach ($routes as $route => $params) {
                        if (isset($params['namespace']) && $params['namespace'] != '') {
                            $args = ['namespace' => $params['namespace']];
                        }
                        if (isset($params['controller']) && $params['controller'] != '') {
                            $args['controller'] = $params['controller'];
                        }
                        if (isset($params['method']) && $params['method'] != '') {
                            $args['method'] = $params['method'];
                        }
                        if (isset($route)) {
                            $this->rooter->add($mthd, $route, $args);
                        }
                    }
                }
            }
            return $this->rooter;
        }
        return null;
    }
}