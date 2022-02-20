<?php

declare(strict_types=1);

use function get_class;

class LoggerFactory
{
    /**
     * @param string $handler
     * @param array $options
     * @return LoggerInterface
     */
    public function create(?string $file, string $handler, ?string $defaultLogLevel, array $options = []): LoggerInterface
    {
        $newHandler = ($handler != null) ? new $handler($file, $defaultLogLevel, $options) : new NativeLoggerHandler($file, $defaultLogLevel, $options);
        if (!$newHandler instanceof LoggerHandlerInterface) {
            throw new LoggerHandlerInvalidArgumentException(get_class($newHandler) . ' is invald as it does not implement the correct interface.');
        }
        return new Logger($newHandler);
    }
}