<?php

declare(strict_types=1);

interface ThemeBuilderInterface
{
    public static function theme(string $key): mixed;
}