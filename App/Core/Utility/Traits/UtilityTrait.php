<?php

declare(strict_types=1);

trait UtilityTrait
{
    public function security(string $key): mixed
    {
        return YamlFile::get('app')['security'][$key];
    }

    public static function appSecurity(string $key): mixed
    {
        return YamlFile::get('app')['security'][$key];
    }
}