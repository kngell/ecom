<?php

declare(strict_types=1);

class DataAccessLayerManager
{
    protected string $tableSchema;
    protected string $tableSchameID;
    protected array $options;
    protected ContainerInterface $container;

    /**
     * Main contructor
     *=====================================================================.
     * @param DataMapperEnvironmentConfig $datamapperEnvConfig
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(private DataMapperEnvironmentConfig $dataMapperEnvConfig)
    {
        $this->container = Container::getInstance();
    }

    public function setParams(string $tableSchema, string $tableSchemaID, ?array $options = []) : self
    {
        $this->tableSchema = $tableSchema;
        $this->tableSchameID = $tableSchemaID;
        $this->options = $options;
        return $this;
    }

    public function setCredentials() : self
    {
        $this->dataMapperEnvConfig->setCredentials(YamlFile::get('database'));
        return $this;
    }

    /**
     * Initializind ORM DataBase Management
     * =====================================================================.
     * @return void
     */
    public function initialize()
    {
        $emFactory = $this->container->make(EntityManagerFactory::class);
        $emFactory->getDataMapper()->setCredentials($this->dataMapperEnvConfig->getCredentials('mysql'));
        return $emFactory->create($this->tableSchema, $this->tableSchameID, $this->options);
    }
}