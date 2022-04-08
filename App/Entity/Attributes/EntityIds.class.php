<?php

declare(strict_types=1);
#[Attribute]
class EntitIds
{
    public function __construct(public string $argument)
    {
    }
}