<?php

declare(strict_types=1);

interface ClientFormBuilderInterface
{
    /**
     * Build the form ready for the view render. One argument required
     * which is the action where the form will be posted.
     *
     * @param string $action - form action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return mixed
     */
    public function createForm(string $action, ?Object $dataRepository = null, ?object $callingController = null): mixed;
}