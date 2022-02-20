<?php

declare(strict_types=1);

abstract class AbstractDatabaseDriver implements DatabaseDriverInterface
{
    /** @var array - PDO Parameters */
    protected array $params = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    /**
     * @var object|null
     */
    private ?object $dbh;

    public function PdoParams(): array
    {
        return $this->params;
    }

    /**
     * Close the database connection.
     *
     * @return void
     */
    public function close()
    {
        $this->dbh = null;
    }
}