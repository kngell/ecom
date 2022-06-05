<?php

declare(strict_types=1);

class ClientFormBuilder extends FormBuilder
{
    /**
     * @var mixed
     */
    protected ?RepositoryInterface $repositoryObject;

    /**
     * Main purpose of this constructor is to provide an easy way for us
     * to access the correct data repository from our form builder class
     * We only need to type hint the class to the parent constructor
     * within the constructor of our form builder class. Only instances of
     * data repository is allowed will throw an exception otherwise.
     *
     * @param string|null $repositoryObjectName - the name of the repository we want to instantiate
     */
    public function __construct(?Object $repository = null)
    {
        $this->repositoryObject = $repository;
        parent::__construct();
    }

    /**
     * Check the repository isn't Null.
     *
     * @return bool
     */
    public function hasRepo() : bool
    {
        if (!$this->repositoryObject) {
            throw new FormBuilderInvalidArgumentException($this->repositoryObject::class . ' repository has returned null. Repository is only valid if your editing existing data.');
        }
        return true;
    }

    /**
     * Return the repository object.
     *
     * @return object
     */
    public function getRepo() : Object
    {
        if ($this->hasRepo()) {
            return $this->repositoryObject;
        }
    }

    public function setRepo(RepositoryInterface $repository) : self
    {
        $this->repositoryObject = $repository;
        return $this;
    }

    /**
     * Cast repository object to an array.
     *
     * @param object $data
     * @return bool|array
     */
    public function castArray(Object $data): bool|array
    {
        if ($data != null) {
            return (array) $data;
        }
        return false;
    }
}