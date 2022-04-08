<?php

declare(strict_types=1);

interface LoggerHandlerInterface
{
    public function write(string $level, string $message, array $context = []): void;
}