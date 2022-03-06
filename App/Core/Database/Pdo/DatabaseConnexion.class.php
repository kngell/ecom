<?php

declare(strict_types=1);

class DatabaseConnexion implements DatabaseConnexionInterface
{
    /**
     * @var array
     */
    private array $credentials;
    /**
     * @var PDO
     */
    private PDO $con;

    public function __construct()
    {
    }

    /**
     * SetCredentials
     * =========================================================.
     * @param array $credentials
     * @return void
     */
    public function setCredentials(array $credentials) : void
    {
        $this->credentials = $credentials;
    }

    /**
     * @inheritDoc
     */
    public function open() :PDO
    {
        // Set Options
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET UTF8mb4',
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ];
        if (!isset($this->con)) {
            try {
                $this->con = new PDO($this->credentials['dsn'], $this->credentials['dbUser'], $this->credentials['dbPass'], $options);
            } catch (PDOException $e) {
                throw new DatabaseConnexionExceptions($e->getMessage(), (int) $e->getCode());
            }
        }
        return $this->con;
    }

    /**
     * @inheritDoc
     */
    public function close():void
    {
        $this->con = null;
    }

    /**
     * Get the value of credentials.
     *
     * @return  array
     */
    public function getCredentials() : array
    {
        return $this->credentials;
    }

    /**
     * Get the value of con.
     *
     * @return  PDO
     */
    public function getConnexion() : PDO
    {
        return $this->con;
    }
}