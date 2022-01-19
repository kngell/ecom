<?php

declare(strict_types=1);

abstract class AbstractModel extends Model implements ModelInterface
{
    /**
     * Prevent Deleting Ids
     * ------------------------------------------------------------.
     * @return array
     */
    abstract public function gardedId() : array;
}