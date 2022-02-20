<?php

declare(strict_types=1);
class ConstantConfig
{
    /**
     * DSEPERATOR ALIASES
     * -----------------------------------------------------------------------.
     * @return self
     */
    public function ds() : self
    {
        defined('URL_SEPARATOR') or define('URL_SEPARATOR', '/');
        defined('PS') or define('PS', PATH_SEPARATOR);
        defined('US') or define('US', URL_SEPARATOR);
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * Application Constant.
     * -----------------------------------------------------------------------.
     * @param string $appRoot
     * @return self
     */
    public function appConstants(string $appRoot) : self
    {
        defined('APP_ROOT') or define('APP_ROOT', $appRoot);
        defined('CONFIG_PATH') or define('CONFIG_PATH', APP_ROOT . DS . 'Config' . DS . 'Yaml');
        defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', APP_ROOT . DS . 'App' . DS);
        defined('LOG_DIR') or define('LOG_DIR', APP_ROOT . DS . 'Temp' . DS . 'Log');
        return $this;
    }
}