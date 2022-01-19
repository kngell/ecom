<?php

declare(strict_types=1);

class DataAccessLayerManager
{
    protected string $tableSchema;
    protected string $tableSchameID;
    protected DataMapperEnvironmentConfig $datamapperEnvConfig;
    protected array $options;
    protected ContainerInterface $container;

    /**
     * Main contructor
     *=====================================================================.
     * @param DataMapperEnvironmentConfig $datamapperEnvConfig
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(DataMapperEnvironmentConfig $env, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->datamapperEnvConfig = $env;
        $this->container = Container::getInstance();
        $this->tableSchema = $tableSchema;
        $this->tableSchameID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * Initializind ORM DataBase Management
     * =====================================================================.
     * @return void
     */
    public function initialize()
    {
        $entitymanagerFactory = $this->container->make(EntityManagerFactory::class);
        $this->container->bind(CrudInterface::class, fn () => new Crud($entitymanagerFactory->getDatamapper(), $entitymanagerFactory->getQuerybuilder(), $this->tableSchema, $this->tableSchameID, $this->options));
        return $entitymanagerFactory->create();
    }
}