<?php

declare(strict_types=1);

defined('ROOT_DIR') or define('ROOT_DIR', realpath(dirname(__DIR__)));
$autoload = ROOT_DIR . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

try {
    /* Attempting to run a single instance of the application */
    Application::getInstance()
        ->setPath(ROOT_DIR)
        ->setConst()
        ->setErrorHandler(YamlFile::get('app')['error_handler'], E_ALL)
        ->setConfig(YamlFile::get('app'))
        ->setSession(YamlFile::get('app')['session'], null, true)
        ->setCookie([])
        ->setCache(YamlFile::get('app')['cache'], null, true)
        ->setRoutes(YamlFile::get('routes'))
        ->setContainerProviders(YamlFile::get('providers'))
        ->run();
} catch (\Throwable $e) {
    throw new BaseResourceNotFoundException($e->getMessage(), $e->getCode());
}