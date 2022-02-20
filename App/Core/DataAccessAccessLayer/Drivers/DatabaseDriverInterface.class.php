<?php

declare(strict_types=1);

interface DatabaseDriverInterface extends DatabaseConnexionInterface
{
    public function open(): PDO;

    public function close() : void;
}