<?php

declare(strict_types=1);

interface TreeBuilderInterface
{
    public function buildChildTreeView(array $items) : array;

    public function buildSChildStandardTreeView(array $items, int $parentID = 0): array;
}