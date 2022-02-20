<?php

declare(strict_types=1);

class EntityManagerFactory
{
    /**
     *propertty.
     */
    private null|DataMapperInterface $datamapper;
    /**
     *property.
     */
    private null|QueryBuilderInterface $querybuilder;

    private ContainerInterface $container;

    /**
     * Main constructor
     * =====================================================================.
     *
     * @param DataMapperInterface $datamapper
     * @param QueryBuilderInterface $querybuilder
     */
    public function __construct(?DataMapperInterface $datamapper = null, ?QueryBuilderInterface $querybuilder = null)
    {
        $this->container = Container::getInstance();
        $this->datamapper = $datamapper;
        $this->querybuilder = $querybuilder;
    }

    /**
     * Create EntityManager
     * =====================================================================.
     *
     * @param string $crudString
     * @param string $tableSchma
     * @param string $tableShameID
     * @param array $options
     * @return EntityManagerInterface
     */
    public function create() : EntityManagerInterface
    {
        $this->container->bind(EntityManagerInterface::class, fn () => $this->container->make(EntityManager::class));
        $em = $this->container->make(EntityManagerInterface::class);
        if (!$em instanceof EntityManagerInterface) {
            throw new EntityManagerExceptions(get_class($em) . ' is not a valid entityManager object!');
        }
        return $em;
    }

    /**
     * Get main constructor.
     */
    public function getDatamapper() : DataMapperInterface
    {
        return $this->datamapper;
    }

    /**
     * Get main constructor.
     */
    public function getQuerybuilder() :QueryBuilderInterface
    {
        return $this->querybuilder;
    }
}
