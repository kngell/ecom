<?php

declare(strict_types=1);

use function get_class;

class LoggerFactory
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param string $handler
     * @param array $options
     * @return LoggerInterface
     */
    public function create(?string $file, ?string $defaultLogLevel, array $options = []): LoggerInterface
    {
        $newHandler = $this->logger->getLoggerHandler()->setParams($file, $defaultLogLevel, $options);
        if (!$newHandler instanceof LoggerHandlerInterface) {
            throw new LoggerHandlerInvalidArgumentException(get_class($newHandler) . ' is invald as it does not implement the correct interface.');
        }
        return $this->logger; //Container::getInstance()->make(LoggerInterface::class)->setParams($newHandler);
    }
}