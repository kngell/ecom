<?php

declare(strict_types=1);
class DispatcherFactory
{
    private ContainerInterface $container;

    public function create() : EventDispatcherInterface
    {
        $dispatcher = $this->container->make(EventDispatcherInterface::class, [
            'listener' => $this->container->make(ListenerProviderInterface::class, [
                'listeners' => YamlFile::get('eventListener'),
                'log' => [],
            ]),
        ]);
        if (!$dispatcher instanceof EventDispatcherInterface) {
            throw new BadEnventDispatcherException($dispatcher::class . ' is not a valid event dispatcher');
        }
        return $dispatcher;
    }
}