<?php

declare(strict_types=1);

class RepositoryFactory
{
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected string $crudIdentifier;
    protected ContainerInterface $container;

    /**
     * Main constructor
     * ==================================================================.
     */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * Init Params
     * ==================================================================.
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @return self
     */
    public function initParams(string $crudIdentifier, string $tableSchema, string $tableSchemaID) : self
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;

        return $this;
    }

    /**
     * Create Data Repository
     *==================================================================.
     * @param string $datarepositoryString
     * @return RepositoryInterface
     */
    public function create() : RepositoryInterface
    {
        $in = $this->initializeDataAccessManager();
        $repositoryObject = $this->container->make(RepositoryInterface::class);
        if (!$repositoryObject instanceof RepositoryInterface) {
            throw new BaseUnexpectedValueException(get_class($repositoryObject) . ' is not a valid repository Object!');
        }
        return $repositoryObject;
    }

    public function initializeDataAccessManager()
    {
        $dbAccessLayer = $this->container->make(DataAccessLayerManager::class)->setParams($this->tableSchema, $this->tableSchemaID);
        $dbAccessLayer->setCredentials();
        return $dbAccessLayer->initialize();
    }
}