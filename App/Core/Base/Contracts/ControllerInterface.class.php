<?php

declare(strict_types=1);

interface ControllerInterface
{
    /**
     * Call real method
     * -----------------------------------------------.
     * @param [type] $name
     * @param [type] $argument
     * @return void
     */
    public function __call($name, $argument) : void;

    /**
     * Render View
     * ----------------------------------------------------.
     * @param string $viewName
     * @param array $context
     * @return void
     */
    public function render(string $viewName, array $context = []) : void;
}