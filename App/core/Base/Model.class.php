<?php

declare(strict_types=1);
class Model extends ModelCruds
{
    protected Object $repository;
    protected ContainerInterface $container;
    protected MoneyManager $money;
    protected Entity $entity;
    protected $validates = true;
    protected $_results;
    protected $_count;
    protected $_modelName;
    protected $_softDelete = false;
    protected $_deleted_item = false;
    protected $_current_ctrl_method = 'update';
    protected $validationErr = [];
    protected $_lasID;

    /**
     * Main Constructor
     * =======================================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema, string $tableSchemaID)
    {
        $this->container = Container::getInstance();
        $this->throwException($tableSchema, $tableSchemaID);
        $this->createRepository($tableSchema, $tableSchemaID);
        $this->entity = $this->container->make(str_replace(' ', '', ucwords(str_replace('_', ' ', $tableSchema))) . 'Entity');
    }

    /**
     * Soft Delete
     * =======================================================================.
     * @param [type] $value
     * @return self
     */
    public function softDelete($value) : self
    {
        $this->_softDelete = $value;
        return $this;
    }

    /**
     * Current Controller Method
     * =======================================================================.
     * @param string $value
     * @return self
     */
    public function current_ctrl_method(string $value) : self
    {
        $this->_current_ctrl_method = $value;
        return $this;
    }

    /**
     * Get Data Repository method
     * ===============================================================.
     * @return DataRepositoryInterface
     */
    public function getRepository() : Repository
    {
        return $this->repository;
    }

    /**
     * Create the model repositories
     * =============================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @return void
     */
    public function createRepository(string $tableSchema, string $tableSchemaID): void
    {
        $this->repository = $this->container->make(RepositoryFactory::class)->initParams('crudIdentifier', $tableSchema, $tableSchemaID)->create();
    }

    /**
     * Throw an exception
     * ================================================================.
     * @return void
     */
    public function throwException(string $tableSchema, string $tableSchemaID): void
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new BaseInvalidArgumentException('Your repository is missing the required constants. Please add the TABLESCHEMA and TABLESCHEMAID constants to your repository.');
        }
    }

    /**
     * Get the value of entity.
     */
    public function getEntity() : Entity
    {
        return $this->entity;
    }

    /**
     * Set the value of entity.
     *
     * @return  self
     */
    public function setEntity(Entity $entity) : self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get Results
     * ===========================================================.
     * @return mixed
     */
    public function get_results() : mixed
    {
        return isset($this->_results) ? $this->_results : [];
    }

    /**
     * Get Detail
     * ===========================================================.
     * @param mixed $id
     * @param string $colID
     * @return self|null
     */
    public function getDetails(mixed $id, string $colID = '') : ?self
    {
        $data_query = ['where' => [$colID != '' ? $colID : $this->get_colID() => $id], 'return_mode' => 'class'];
        return $this->findFirst($data_query);
    }

    /**
     * Get Col ID or TablschemaID.
     *
     * @return string
     */
    public function get_colID() : string
    {
        return isset($this->_colID) ? $this->_colID : '';
    }
}
