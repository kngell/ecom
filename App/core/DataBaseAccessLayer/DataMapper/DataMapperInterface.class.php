<?php

declare(strict_types=1);

interface DataMapperInterface
{
    /**
     * --------------------------------------------------------------------------------------------------
     * Prepare the query string.
     * @param string $sql
     * @return self
     */
    public function prepare(string $sql) : self;

    /**
     * Bind (Original)
     * --------------------------------------------------------------------------------------------------.
     * @param mixed $value
     * @return int
     */
    public function bind_type(mixed $value) : int;

    /**
     * Bind params.
     * -------------------------------------------------------------------------------------------------.
     * @param mixed $param
     * @param mixed $value
     * @param [type] $type
     * @return void
     */
    public function bind(mixed $param, mixed $value, $type = null);

    /**
     * --------------------------------------------------------------------------------------------------
     * combinaition method wich combines bind type and values.
     *@param array $fields
     *@param bool $isSearch
     *@return self
     */
    public function bindParameters(array $fields = [], bool $isSearch = false) : self;

    /**
     * --------------------------------------------------------------------------------------------------
     * Return number of rows.
     * @return int
     */
    public function numrow(): int;

    /**
     * --------------------------------------------------------------------------------------------------
     * Execute prepare statement.
     * @return void
     */
    public function execute(): mixed;

    /**
     * --------------------------------------------------------------------------------------------------
     * Return sigle object result.
     *@return object
     */
    public function result(): Object;

    /**
     * --------------------------------------------------------------------------------------------------
     * Return all.
     * @param array $options
     * @return self
     */
    public function results(array $options) : self;

    /**
     * --------------------------------------------------------------------------------------------------
     * Get las insert ID.
     * @return int
     * @throws throwable
     */
    public function getLasID(): int;
}