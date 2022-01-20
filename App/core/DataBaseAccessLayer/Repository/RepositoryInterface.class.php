<?php

declare(strict_types=1);

interface RepositoryInterface
{
    /**
     * Create or inert into a database
     * --------------------------------------------------------------------------------------------------.
     * @param array $fields
     * @return int
     */
    public function create(array $fields) : int;

    /**
     * Delete from database
     * --------------------------------------------------------------------------------------------------.
     * @param array $conditions
     * @return int
     */
    public function delete(array $conditions) : int;

    /**
     * --------------------------------------------------------------------------------------------------
     * Find by ID.
     * @param int $id
     * @return DataMapperInterface
     */
    public function findByID(int $id) : DataMapperInterface;

    /**
     * Find All.
     * --------------------------------------------------------------------------------------------------.
     * @return array
     */
    public function findAll() : mixed;

    /**
     * find By
     * --------------------------------------------------------------------------------------------------.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) : mixed;

    /**
     * Find One by
     *--------------------------------------------------------------------------------------------------.
     * @param array $conditions
     * @param array $options
     * @return mixed
     */
    public function findOneBy(array $conditions, array $options) : mixed;

    /**
     * Find Object
     *--------------------------------------------------------------------------------------------------.
     * @param array $conditions
     * @param array $selectors
     * @return object
     */
    public function findObjectBy(array $conditions = [], array $selectors = []) : Object;

    /**
     * Search data
     *--------------------------------------------------------------------------------------------------.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return array
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) :array;

    /**
     * Find by Id and Delete
     *--------------------------------------------------------------------------------------------------.
     * @param array $conditions
     * @return bool
     */
    public function findByIDAndDelete(array $conditions) :bool;

    /**
     * Find by id and update
     *--------------------------------------------------------------------------------------------------.
     * @param array $fields
     * @param int $id
     * @return bool
     */
    public function findByIdAndUpdate(array $fields = [], int $id = 0) : bool;

    /**
     * Search data with pagination
     *--------------------------------------------------------------------------------------------------.
     * @param array $args
     * @param object $request
     * @return array
     */
    public function findWithSearchAndPagin(array $args, Object $request) : array;

    /**
     * find and return self for chanability
     *--------------------------------------------------------------------------------------------------.
     * @param int $id
     * @param array $selectors
     * @return self
     */
    public function findAndReturn(int $id, array $selectors = []) : self;

    /**
     * Get Table columns.
     *
     * @param array $options
     * @return object
     */
    public function get_tableColumn(array $options): object;
}
