<?php

declare(strict_types=1);
interface FilesSystemInterface
{
    public function get(string $folder, string $file = '') : mixed;

    public function createDir(string $folder) : bool;
}