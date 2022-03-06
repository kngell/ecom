<?php

declare(strict_types=1);

class ClientFormBuilder extends FormBuilder
{
    /**
     * @var mixed
     */
    protected mixed $repositoryObject;

    protected string $repositoryObjectName;

    /**
     * Main purpose of this constructor is to provide an easy way for us
     * to access the correct data repository from our form builder class
     * We only need to type hint the class to the parent constructor
     * within the constructor of our form builder class. Only instances of
     * data repository is allowed will throw an exception otherwise.
     *
     * @param string|null $repositoryObjectName - the name of the repository we want to instantiate
     */
    public function setParams(?string $repositoryObjectName = null) : self
    {
        if ($repositoryObjectName != null) {
            $this->repositoryObjectName = $repositoryObjectName;
            $repositoryObject = new $repositoryObjectName();
            if (!$repositoryObject) {
                throw new FormBuilderInvalidArgumentException('Invalid repository');
            }
            $this->repositoryObject = $repositoryObject;
            return $this;
        }
    }

    /**
     * Check the repository isn't Null.
     *
     * @return bool
     */
    public function hasRepo() : bool
    {
        if (!$this->repositoryObject) {
            throw new FormBuilderInvalidArgumentException($this->repositoryObjectName . ' repository has returned null. Repository is only valid if your editing existing data.');
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