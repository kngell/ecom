<?php

declare(strict_types=1);

class ThemeBuilderFactory
{
    protected object $themeDefault;

    /**
     * Create the themeBuilder object and pass the theme builder library defaults to UIKIT.
     *
     * @param string $themeDefault
     * @param array $themeOptions
     * @return object
     */
    public function create(string $themeDefault, array $themeOptions = [])
    {
        $_themeDefault = new $themeDefault($themeOptions);
        if (!$_themeDefault) {
            throw new ThemeBuilderInvalidArgumentException('Invalid theme builder object library. Ensure you are implementing the correct interface [ThemeBuilderInterface]');
        }
        return new ThemeBuilder();
        //return (new ThemeBuilder())->build($_themeDefault);
    }
}