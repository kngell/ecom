<?php

declare(strict_types=1);
interface ApplicationInterface
{
    public function run() :self;

    public function setrouteHandler() : self;
}
