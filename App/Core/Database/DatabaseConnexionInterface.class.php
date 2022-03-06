<?php

declare(strict_types=1);

interface DatabaseConnexionInterface
{
    public function setCredentials(array $credentials) : void;

    /**
     * DataBase open
     * --------------------------------------------------------------------------------------------------.
     * @return PDO
     */
    public function open(): PDO;

    /**
     * Data Base close
     * --------------------------------------------------------------------------------------------------.
     * @return void
     */
    public function close():void;
}