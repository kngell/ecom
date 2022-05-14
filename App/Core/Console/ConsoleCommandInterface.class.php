<?php

declare(strict_types=1);

interface ConsoleCommandInterface
{
    public function dispatch(): int;
}