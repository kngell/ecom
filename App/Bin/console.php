<?php

declare(strict_types=1);

defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(__DIR__)));
$composer = ROOT_PATH . '/vendor/autoload.php';
if (is_file($composer)) {
    require $composer;
}
$console = (new Console())->create();
exit($console);