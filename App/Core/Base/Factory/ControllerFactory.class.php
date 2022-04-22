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
            'params' => $this->getParams(),
        ]);
        if (!$controllerObject instanceof Controller) {
            throw new BadControllerExeption($this->controllerString . ' is not a valid Controller');
        }
        $this->container->bind('controller', fn () => $controllerObject);
        $this->container->bind('method', fn () => $this->method);
        return $controllerObject;
    }

    private function getParams() : array
    {
        $ppr = [];
        foreach (YamlFile::get('controller_properties') as $prop => $class) {
            if ($prop === 'dispatcher') {
                $ppr[$prop] = $this->container->make($class)->create();
            } else {
                $ppr[$prop] = $this->container->make($class);
            }
        }
        return array_merge($ppr, [
            'controller' => $this->controllerString,
            'method' => $this->method,
            'routeParams' => $this->params,
            'filePath' => $this->path,
            'container' => $this->container,
        ]);
    }
}