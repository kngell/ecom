<?php

declare(strict_types=1);

class EntityManagerFactory
{
    private ContainerInterface $container;

    /**
     * Main constructor
     * =====================================================================.
     *
     * @param DataMapperInterface $datamapper
     * @param QueryBuilderInterface $querybuilder
     */
    public function __construct(private DataMapperInterface $dataMapper, private QueryBuilderInterface $queryBuilder)
    {
        $this->container = Container::getInstance();
    }

    /**
     * Create EntityManager
     * =====================================================================.
     *
     * @param string $tableSchma
     * @param string $tableShameID
     * @param array $options
     * @return EntityManagerInterface
     */
    public function create(string $tableSchema, string $tableSchmaID, array $options) : EntityManagerInterface
    {
        $em = $this->container->make(EntityManagerInterface::class);
        $em->getCrud()->setParams($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchmaID, $options);
        if (!$em instanceof EntityManagerInterface) {
            throw new EntityManagerExceptions(get_class($em) . ' is not a valid entityManager object!');
        }
        return $em;
    }

    /**
     * Get main constructor.
     */
    public function getDataMapper() : DataMapperInterface
    {
        return $this->dataMapper;
    }

    /**
     * Get main constructor.
     */
    public function getQueryMuilder() :QueryBuilderInterface
    {
        return $this->queryBuilder;
    }
}