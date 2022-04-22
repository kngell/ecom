<?php

declare(strict_types=1);

class Repository extends AbstractRepository implements RepositoryInterface
{
    protected EntityManagerInterface $em;

    /**
     * Main constructor
     * ====================================================================.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function entity(Entity $entity) : self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Create new entrie
     * ====================================================================.
     * @param array $fields
     * @return int
     */
    public function create(array $fields = []) :int
    {
        try {
            return $this->em->getCrud()->create($this->fields());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Delete from data base
     * ====================================================================.
     * @param array $conditions
     * @return int|null
     */
    public function delete(array $conditions = []) : int
    {
        try {
            return $this->em->getCrud()->delete($conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(array $conditions) : ?int
    {
        try {
            return $this->em->getCrud()->update($this->fields(), $conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find by ID
     * ====================================================================.
     * @param int $id
     * @return DataMapperInterface
     */
    public function findByID(int $id): DataMapperInterface
    {
        if ($this->isEmpty($id)) {
            try {
                return $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id], []);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    /**
     * Find One element by
     *====================================================================.
     * @param array $conditions
     * @param array $options
     * @return mixed
     */
    public function findOneBy(array $conditions = [], array $options = []) : mixed
    {
        if ($this->isArray($conditions)) {
            try {
                return $this->em->getCrud()->read([], $conditions, [], $options);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return false;
    }

    /**
     * Get All
     * ====================================================================.
     * @return array
     */
    public function findAll(): mixed
    {
        try {
            return $this->findBy();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get All By
     * ====================================================================.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) : mixed
    {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $parameters, $options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findObjectBy(array $conditions = [], array $selectors = []): object
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->get($selectors, $conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Search Data
     *====================================================================.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return array
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->search($selectors, $conditions, $parameters, $options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find data and delete it
     * ====================================================================.
     * @param array $conditions
     * @return bool
     */
    public function findByIDAndDelete(array $conditions): bool
    {
        if ($this->isArray($conditions)) {
            try {
                $result = $this->findOneBy($conditions, []);
                if ($result != null && $result > 0) {
                    $delete = $this->em->getCrud()->delete($conditions);
                    if ($delete) {
                        return true;
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    /**
     * Find and Update
     * ====================================================================.
     * @param array $fields
     * @param int $id
     * @return bool
     */
    public function findByIdAndUpdate(array $fields = [], int $id = 0): bool
    {
        $this->isArray($fields);
        try {
            $result = $id > 0 ? $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id], []) : null;
            if ($result != null && count($result) > 0) {
                $params = (!empty($fields)) ? array_merge([$this->im->getCrud()->getSchemaID() => $id], $fields) : $fields;
                $update = $this->em->getCrud()->update($params, $this->im->getCrud()->getSchemaID());
                if ($update) {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Search with pagination
     *====================================================================.
     * @param array $arg
     * @param object $request
     * @return array
     */
    public function findWithSearchAndPagin(array $args, Object $request): array
    {
        list($conditions, $totalRecords) = $this->getCurrentQueryStatus($request, $args);
        /** @var Sortable */
        $sorting = new Sortable($args['sort_columns']);
        /** @var Paginator */
        $pagin = new Paginator($totalRecords, $args['records_per_page'], $request->query->getInt('page', 1));
        $parameters = ['limit' => $args['records_per_page'], 'offset' => $pagin->getOffset()];
        $optionnals = ['orderby' => $sorting->getColumn() . ' ' . $sorting->getDirection()];

        if ($request->query->getAlnum($args['filter_alias'])) {
            $searchRequest = $request->query->getAlnum($args['filter_alias']);
            if (is_array($args['filter_by'])) {
                for ($i = 0; $i < count($args['filter_by']); $i++) {
                    $searchCond = [$args['filter_by'][$i] => $searchRequest];
                }
            }
            $results = $this->findBySearch($args['filter_by'], $searchCond);
        } else {
            $queryCond = array_merge($args['additional_conditions'], $conditions);
            $results = $this->findBy($args['selectors'], $queryCond, $parameters, $optionnals);
        }

        return [
            $results->get_results(),
            $pagin->getPage(),
            $pagin->getTotalPages(),
            $totalRecords,
            $sorting->sortDirection(),
            $sorting->sortDescAsc(),
            $sorting->getClass(),
            $sorting->getColumn(),
            $sorting->getDirection(),
        ];
    }

    /**
     * Find and retur self
     *====================================================================.
     * @param int $id
     * @param array $selectors
     * @return RepositoryInterface
     */
    public function findAndReturn(int $id, array $selectors = []): RepositoryInterface
    {
        return $this;
    }

    public function get_tableColumn(array $options): object
    {
        try {
            return $this->em->getCrud()->showColumns($options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getCurrentQueryStatus(Object $request, array $args)
    {
        $totalRecords = 0;
        $req = $request->query;
        $status = $req->getAlnum($args['query']);
        $searchResults = $req->getAlnum($args['filter_alias']);
        if ($searchResults) {
            for ($i = 0; $i < count($args['filter_by']); $i++) {
                $conditions = [$args['filter_by'][$i] => $searchResults];
                $totalRecords = $this->em->getCrud()->countRecords($conditions, $args['filter_by'][$i]);
            }
        } elseif ($status != '') {
            $conditions = [$args['query'] => $status];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        } else {
            $conditions = [];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        }
        return [
            $conditions,
            $totalRecords,
        ];
    }
}