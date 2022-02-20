<?php

declare(strict_types=1);

class MysqlDatabaseConnection extends AbstractDatabaseDriver
{
    /** @var string */
    protected const PDO_DRIVER = 'mysql';

    /** @var string */
    protected string $pdoDriver;
    private object $environment;

    /**
     * Class constructor. piping the class properties to the constructor
     * method. The constructor will throw an exception if the database driver
     * doesn't match the class database driver.
     *
     * @param object $environment
     * @param string $pdoDriver
     * @return void
     */
    public function __construct(object $environment, string $pdoDriver)
    {
        $this->environment = $environment;
        $this->pdoDriver = $pdoDriver;
        if (self::PDO_DRIVER !== $this->pdoDriver) {
            throw new DataAccessLayerInvalidArgumentException($pdoDriver . ' Invalid database driver pass requires ' . self::PDO_DRIVER . ' driver option to make your connection.');
        }
    }

    /**
     * Opens a new Mysql database connection.
     *
     * @return PDO
     * @throws DataLayerException
     */
    public function open(): PDO
    {
        try {
            return new PDO($this->environment->getDsn(), $this->environment->getDbUsername(), $this->environment->getDbPassword(), $this->params);
        } catch (PDOException $e) {
            throw new DataAccessLayerException($e->getMessage(), (int) $e->getCode());
        }
    }
}