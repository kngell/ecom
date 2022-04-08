<?php

declare(strict_types=1);

interface ModelInterface
{
    public function setCount(int $count) : void;

    public function setResults(mixed $results) : void;

    public function count() : int;

    public function getRepository() : RepositoryInterface;

    public function getEntity() : Entity;
}