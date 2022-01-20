<?php

declare(strict_types=1);
interface DatatableInterface
{
    /**
     * Create method
     * --------------------------------------------------------------------------------------------------.
     * @param string $datacolumnString
     * @param array $datarepository
     * @param array $sortcontrollerArg
     * @return self
     */
    public function create(string $datacolumnString, array $datarepository, array $sortcontrollerArg) : self;

    /**
     * Table / Build out HTML table
     * --------------------------------------------------------------------------------------------------.
     * @return string|null
     */
    public function table() : ?string;

    /**
     * Pagination
     * --------------------------------------------------------------------------------------------------.
     * @return string|null
     */
    public function pagination() : ?string;
}
