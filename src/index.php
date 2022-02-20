<?php

declare(strict_types=1);

defined('ROOT_DIR') or define('ROOT_DIR', realpath(dirname(__DIR__)));
$autoload = ROOT_DIR . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}
// $app = (new Application())->setAppRoot(ROOT_DIR);
// $app->setConst()->setSession()->handleCors()->run();

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
        ->setLogger(LOG_DIR, YamlFile::get('app')['logger_handler']['file'], LogLevel::DEBUG, [])
        ->setContainerProviders(YamlFile::get('providers'))
        ->setThemeBuilder(YamlFile::get('app')['theme_builder'], true)
        ->run();
} catch (Exception $e) {
    echo $e->getMessage();
}