<?php

declare(strict_types=1);

class EntityManager implements EntityManagerInterface
{
    /**
     * Main constructor
     * =====================================================================.
     * @param CrudInterface $crud
     * @return void
     */
    public function __construct(private CrudInterface $crud)
    {
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