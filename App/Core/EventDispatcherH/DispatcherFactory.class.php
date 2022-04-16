<?php

declare(strict_types=1);
class DispatcherFactory
{
    private ContainerInterface $container;

    public function create() : DispatcherInterface
    {
        $dispatcher = $this->container->make(DispatcherInterface::class, [
            'listeners' => YamlFile::get('eventListener'),
            'log' => [],
        ]);
        if (!$dispatcher instanceof DispatcherInterface) {
            throw new BadDispatcherExeption($dispatcher::class . ' is not a valid Controller');
        }
        return $dispatcher;
    }
}