<?php

declare(strict_types=1);

class EntityManager implements EntityManagerInterface
{
    /**
     * @var CrudInterface
     */
    private CrudInterface $crud;

    /**
     * Main constructor
     * =====================================================================.
     * @param CrudInterface $crud
     * @return void
     */
    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * Get Items
     * =====================================================================.
     *@inheritDoc
     */
    public function getCrud(): CrudInterface
    {
        return $this->crud;
    }
}