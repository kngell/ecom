<?php

declare(strict_types=1);

interface ConsoleInterface
{
    public function create();

    public function registerCommands();
}