<?php

declare(strict_types=1);

abstract class AbstractDatatableColumns implements DatatableColumnInterface
{
    abstract public function columns() : array;
}
