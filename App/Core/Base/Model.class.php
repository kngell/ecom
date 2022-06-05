<?php

declare(strict_types=1);
class Model extends AbstractModel
{
    use ModelTrait;

    protected ContainerInterface $container;
    protected MoneyManager $money;
    protected Entity $entity;
    protected ModelHelper $helper;
    protected SessionInterface $session;
    protected CookieInterface $cookie;
    protected CacheInterface $cache;
    protected Token $token;
    protected RequestHandler $request;
    protected ResponseHandler $response;
    protected Validator $validator;
    protected bool $validates = true;
    protected array $validationErr = [];
    protected string $tableSchema;
    protected string $tableSchemaID;
    private $_results;
    private int $_count;
    private bool $_softDelete = false;
    private bool $_deleted_item = false;
    private string $_current_ctrl_method = 'update';
    private int $_lasID;
    private string $_matchingTestColumn;

    /**
     * Main Constructor
     * =======================================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema, string $tableSchemaID, $matchingTestCol = '')
    {
        $this->throwException($tableSchema, $tableSchemaID);
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->properties();
        $this->_modelName = $this::class;
    }

    public function guardedID(): array
    {
        return [];
    }

    public function getTableName() : string
    {
        return $this->tableSchema;
    }

    public function getLastID() : ?int
    {
        if (isset($this->_lasID)) {
            return $this->_lasID;
        }
        return null;
    }

    public function setLastID(int $lastID) : self
    {
        $this->_lasID = $lastID;
        return $this;
    }

    /**
     * Get Detail
     * ===========================================================.
     * @param mixed $id
     * @param string $colID
     * @return self|null
     */
    public function getDetails(mixed $id, string $colID = '', string $mode = 'class') : ?self
    {
        $data_query = $this->table()
            ->where([$colID != '' ? $colID : $this->get_colID() => $id])
            ->return($mode)
            ->build();
        return $this->findFirst($data_query);
    }

    public function getAll() : ?self
    {
        return $this->find();
    }

    public function getUniqueId(string $colid_name = '', string $prefix = '', string $suffix = '', int $token_length = 24) : mixed
    {
        $output = $prefix . $this->token->generate($token_length) . $suffix;
        while ($this->getDetails($output, $colid_name)->count() > 0) :
            $output = $prefix . $this->token->generate($token_length) . $suffix;
        endwhile;
        return $output;
    }

    /**
     * Save Data insert or update
     * ============================================================.
     * @param array $params
     * @return ?object
     */
    public function save(?Entity $entity = null) : ?Object
    {
        $en = is_null($entity) ? $this->entity : $entity;
        if ($this->beforeSave($entity)) {
            if (( new ReflectionProperty($en, $en->regenerateField($en->getColId())))->isInitialized($en)) {
                $en = $this->beforeSaveUpadate($en);
                $save = $this->update($en);
            } else {
                $en = $this->beforeSaveInsert($en);
                $save = $this->insert();
            }
            if ($save->count() > 0) {
                $params['saveID'] = $save ?? '';
                return $this->afterSave($params);
            }
        }
        return null;
    }

    public function getMatchingTestColumn() : string
    {
        return $this->_matchingTestColumn;
    }

    public function validator(array $items = []) : void
    {
        $this->validator->validate($items, $this);
    }

    public function count() : int
    {
        return $this->_count;
    }

    public function setCount(int $count) : void
    {
        $this->_count = $count;
    }

    public function setResults(mixed $results) : void
    {
        $this->_results = $results;
    }

    public function getErrorMessages(array $newKeys = []) : array
    {
        return $this->response->transform_keys($this->validationErr, $newKeys);
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
     * @return RepositoryInterface
     */
    public function getRepository() : RepositoryInterface
    {
        return $this->repository;
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

    public function assign(array $data) : self
    {
        $this->entity->assign($data);
        return $this;
    }

    /**
     * Get the value of entity.
     */
    public function getEntity() : Entity
    {
        return $this->entity;
    }

    public function getMoney() : MoneyManager
    {
        return $this->money;
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
     * Get Col ID or TablschemaID.
     *
     * @return string
     */
    public function get_colID() : string
    {
        return isset($this->_colID) ? $this->_colID : '';
    }

    public function validationPasses() : bool
    {
        return $this->validates;
    }

    public function unsetProperty(string $p) : self
    {
        unset($this->$p);
        return $this;
    }
}