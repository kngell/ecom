<?php

declare(strict_types=1);
interface FileInterface
{
    public function get(string $folder, string $file = '') : mixed;
}
