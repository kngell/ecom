<?php

declare(strict_types=1);

defined('ROOT_DIR') or define('ROOT_DIR', realpath(dirname(__DIR__)));
$autoload = ROOT_DIR . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}
$app = (new Application())->setAppRoot(ROOT_DIR);
$app->setConst()->setSession()->handleCors()->run();