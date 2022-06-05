<?php

declare(strict_types=1);

interface DisplayProductsInterface
{
    public function displayAll(): array;

    public function displaySingle() :  array;
}