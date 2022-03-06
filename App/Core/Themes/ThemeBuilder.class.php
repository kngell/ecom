<?php

declare(strict_types=1);

class ThemeBuilder
{
    protected object $themeBuilder;

    public function create(string $themeBuilder)
    {
        $this->themeBuilder = new $themeBuilder();
        if (!$this->themeBuilder instanceof ThemeBuilderInterface) {
            throw new ThemeBuilderInvalidArgumentException('Invalid theme builder object. Ensure you are implementing the correct interface [ThemeBuilderInterface]');
        }
        return $this->themeBuilder;
    }
}