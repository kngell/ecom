<?php

declare(strict_types=1);

abstract class AbstractRepository
{
    protected Entity $entity;

    public function fields() : array
    {
        return $this->entity->getInitializedAttributes();
    }

    protected function isArray(array $conditions) : bool
    {
        if (!is_array($conditions)) {
            throw new RepositoryInvalidArgumentException('Argument Supplied is not an array');
        }
        return true;
    }

    protected function isEmpty(int $id) : bool
    {
        if (empty($id)) {
            throw new RepositoryInvalidArgumentException('Argument shuold not be empty');
        }

        return true;
    }
}