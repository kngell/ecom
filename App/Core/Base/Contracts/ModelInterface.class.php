<?php

declare(strict_types=1);

interface ModelInterface
{
    public function setCount(int $count) : void;

    public function setResults(mixed $results) : void;

    public function count() : int;

    public function getRepository() : RepositoryInterface;

    public function getEntity() : Entity;

    public function validator(array $items = []) : void;

    public function runValidation(CustomValidator $validator) : void;

    public function validationPasses() : bool;

    public function getErrorMessages() : array;

    public function getMatchingTestColumn() : string;

    public function getAll() : ?self;

    public function table(?string $tbl = null, mixed $columns = null) : QueryParams;

    public function get_results() : mixed;

    public function getUniqueId(string $colid_name = '', string $prefix = '', string $suffix = '', int $token_length = 24) : mixed;

    public function assign(array $data) : self;

    public function save(?Entity $entity = null) : ?Object;
}