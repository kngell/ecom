<?php

declare(strict_types=1);
class ModelFactory
{
    private ContainerInterface $container;

    public function __construct()
    {
    }

    public function create(string $modelString) : ModelInterface
    {
        $modelObject = $this->container->make($modelString);
        if (!$modelObject instanceof ModelInterface) {
            throw new BadControllerExeption($modelString . ' is not a valid Model');
        }
        $this->container->bind($modelString, fn () => $modelObject);
        return $modelObject;
    }
}