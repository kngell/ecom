<?php

declare(strict_types=1);

class FileSystem extends AbstractFiles implements FilesSystemInterface
{
    /**
     * Get Files
     * ==============================================================================.
     * @param string $folder
     * @param string $file
     * @return mixed
     */
    public function get(string $folder, string $file = '') : mixed
    {
        $file = file_exists($folder . $file) ? [$folder . $file] : $this->search_file($folder, $file);
        if (isset($file) && count($file) === 1) {
            $file = current($file);
            $infos = pathinfo($file);

            return match ($infos['extension']) {
                'json' => json_decode(file_get_contents($file), true),
                'php' => '',
            };
        }

        return false;
    }

    public function createDir(string $folder) : bool
    {
        $path = realpath($folder);
        if ($path !== false and is_dir($path)) {
            return true;
        } else {
            try {
                mkdir($folder);
                return true;
            } catch (\Throwable $th) {
                throw new FileSystemManagementException('Impossible de créer le fichier! ' . $th->getMessage(), $th->getCode());
            }
        }
        return false;
    }

    public function files_model_diff(array $clientAry, array $dbAry) : array
    {
        if (isset($clientAry) && isset($dbAry)) {
            foreach ($clientAry as $cm) {
                if (null != $cm) {
                    foreach ($dbAry as $key => $bm) {
                        if ($cm == $bm) {
                            unset($dbAry[$key]);
                        }
                    }
                }
            }
            return array_values($dbAry);
        }
    }

    public function fileAryFromModel(Model $media) : array
    {
        return array_map(function ($m) {
            return basename(unserialize($m->{$m->get_media()})[0]);
        }, $media->get_results());
    }

    public function getFileModel(string $url, Model $model) : ?Model
    {
        if ($model->count() > 0) {
            $m = current(array_filter($model->get_results(), function ($m) use ($url) {
                if (basename(unserialize($m->fileUrl)[0]) == $url) {
                    return $m;
                }
            }));
            return !is_object($m) ? null : $m;
        }
    }

    public function urlsToRemove(array $imgAry, Model $m) : array
    {
        if ($m->count() > 0 && isset($imgAry)) {
            $dbUslrsModel = [];
            foreach ($imgAry as $url) {
                $dbUslrsModel[] = $this->getFileModel($url, $m);
            }
            return $this->files_model_diff($dbUslrsModel, $m->get_results());
        }
        return [];
    }

    public function cleanDbFilesUrls(array $clientAry = [], ?Model $m = null) : bool
    {
        try {
            $urlToRemove = $this->urlsToRemove(!empty($clientAry) ? $clientAry : [], $m);
            if (isset($urlToRemove) && is_array($urlToRemove) && !empty($urlToRemove)) {
                $filesToRemove = [];
                foreach ($urlToRemove as $m) {
                    if ($m->delete()) {
                        $filesToRemove[] = $m;
                    }
                }
                return $this->cleanFilesSystemUrls($filesToRemove);
            }
            return true;
        } catch (\Throwable $th) {
            throw new FileSystemManagementException('Impossible de supprimer les fichiers! ' . $th->getMessage(), $th->getCode());
        }

        return false;
    }

    /**
     * cleanFilesSystemUrls
     * ===========================================================================.
     * @param array $urlsAry
     * @param string $table
     * @return mixed
     */
    public function cleanFilesSystemUrls(array $urlsAry = []) : mixed
    {
        try {
            if (!empty($urlsAry)) {
                foreach ($urlsAry as $m) {
                    $fileToRemove = unserialize($m->{$m->get_media()});
                    if ($fileToRemove != '') {
                        $this->remove_files($fileToRemove, $m);
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            throw new FileSystemManagementException('Impossible de supprimer les fichiers! ' . $th->getMessage(), $th->getCode());
        }
    }

    public function remove_files(mixed $fileToRemove, ?Model $m = null) : bool
    {
        try {
            if (is_string($fileToRemove) && !empty($fileToRemove)) {
                $fileToRemove = [$fileToRemove];
            }
            if (is_array($fileToRemove) && !empty($fileToRemove)) {
                foreach ($fileToRemove as $file) {
                    $urls = $m->getAll(['where' => [$m->get_media() => serialize($fileToRemove)], 'return_mode' => 'class']);
                    if ($urls->count() <= 0) {
                        file_exists(IMAGE_ROOT . $file) ? unlink(IMAGE_ROOT . $file) : '';
                        file_exists(IMAGE_ROOT_SRC . $file) ? unlink(IMAGE_ROOT_SRC . $file) : '';
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            throw new FileSystemManagementException('Impossible de supprimer les fichiers! ' . $th->getMessage(), $th->getCode());
        }
    }

    private function search_file(string $folder, ?string $file_to_search = null, array &$results = []) : array
    {
        $files = ($folder !== false and is_dir($folder)) ? scandir($folder) : false;
        if ($files) {
            foreach ($files as $key => $value) {
                $path = realpath($folder . DS . $value);
                if (!is_dir($path)) {
                    if ($file_to_search == $value) {
                        $results[] = $path;
                    }
                } elseif ($value != '.' && $value != '..') {
                    $this->search_file($path, $file_to_search, $results);
                    if ($file_to_search == $value) {
                        $results[] = $path;
                    }
                }
            }

            return $results;
        }

        return false;
    }
}