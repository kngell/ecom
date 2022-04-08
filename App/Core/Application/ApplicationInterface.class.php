<?php

declare(strict_types=1);
interface ApplicationInterface
{
    public function run();

    public function make(string $abstract, array $args = []): mixed;
}