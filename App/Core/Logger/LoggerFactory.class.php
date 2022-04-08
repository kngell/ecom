<?php

declare(strict_types=1);

use function get_class;

class LoggerFactory
{
    private ContainerInterface $container;

    /**
     * @param string $handler
     * @param array $options
     * @return LoggerInterface
     */
    public function create(?string $file, ?string $defaultLogLevel, array $options = []): LoggerInterface
    {
        $logger = $this->container->make(LoggerInterface::class, [
            'loggerHandler' => $this->container->make(LoggerHandlerInterface::class, [
                'file' => $file,
                'minLevel' => $defaultLogLevel,
                'options' => $options,
            ]),
        ]);
        if (!$logger instanceof LoggerInterface) {
            throw new LoggerHandlerInvalidArgumentException(get_class($logger) . ' is invald as it does not implement the correct interface.');
        }
        return $logger; //Container::getInstance()->make(LoggerInterface::class)->setParams($newHandler);
    }
}