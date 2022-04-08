<?php

declare(strict_types=1);

class FileStorage
{
    /**
     * @return Flatbase
     */
    public function flatDatabase()
    {
        $storage = new Filesystem(STORAGE_PATH . '/files');
        $flatbase = new Flatbase($storage);
        if ($flatbase) {
            return $flatbase;
        }
    }
}