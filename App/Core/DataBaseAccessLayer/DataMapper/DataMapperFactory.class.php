<?php

declare(strict_types=1);

class DataMapperFactory
{
    private ContainerInterface $container;

    /**
     * Main constructor
     * ================================================================.
     *@return void
     */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * Create method
     * ============================================================.
     * @param string $databaseConnexionObject
     * @param string $dataMapperEnvConfigObject
     *@return DataMapperInterface
     */
    public function create(string $databaseConnexionString) : DataMapperInterface
    {
        $databaseConnexionObject = $this->container->make($databaseConnexionString);
        if (!$databaseConnexionObject instanceof DatabaseConnexionInterface) {
            throw new DataMapperExceptions($databaseConnexionString . ' is not a valid database connexion Object!');
        }
        return $this->container->make(DataMapper::class)->setDatabaseConnexion($databaseConnexionObject);
    }
}
