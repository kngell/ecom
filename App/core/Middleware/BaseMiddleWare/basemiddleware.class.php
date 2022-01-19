<?php

declare(strict_types=1);
abstract class BaseMiddleWare implements BasemiddlewareInterface
{
    abstract public function execute();
}
