<?php

declare(strict_types=1);

class Uploader implements UploaderInterface
{
    public function __construct(private FilesManagerInterface $fm, private FilesSystemInterface $fileSytem, private ?Model $model = null)
    {
    }

    public function upload(): string
    {
        if ($this->fm->validate()) {
            $en = $this->model->getEntity();
            if ((new ReflectionProperty($en, $en->getFieldWithDoc('media')))->isInitialized($en)) {
                $incommingPath = $this->fm->getDestinationPath() . DS . $this->fm->getFileName();
                if (file_exists($incommingPath)) {
                    if ($incommingPath == $en->{$en->getFieldWithDoc('media')}) {
                        return $this->model;
                    } else {
                        $this->model->getEntity()->{'set' . ucfirst($this->model->getEntity()->getFieldWithDoc('media'))}(serialize([$incommingPath]));
                        return $this->model;
                    }
                }
            }
            $path = $this->saveFileToDisk();
            if ($path === 'error') {
                throw new UnableToSaveFileOnDiskException('Unable to save file on disk! Please contact the administrator!');
            }
            return $path;
        }
    }

    private function saveFileToDisk()
    {
        $targetFilePath = $this->fm->getDestinationPath();
        $fileName = $targetFilePath . DS . $this->fm->getFileName();
        if ($this->fileSytem->createDir($this->fm->getTargetDir() . DS . $targetFilePath)) {
            if (!file_exists($this->fm->getTargetDir() . DS . $fileName)) {
                if (move_uploaded_file($this->fm->getSourcePath() . DS . $this->fm->getFileName(), $this->fm->getTargetDir() . DS . $fileName)) {
                    if (!file_exists(IMAGE_ROOT_SRC . $targetFilePath) && !in_array($this->fm->getDestinationPath(), ['posts'])) {
                        copy($this->fm->getTargetDir() . DS . $fileName, IMAGE_ROOT_SRC . $fileName);
                    }
                    return $fileName;
                }
                return 'error';
            }
            return  $fileName;
        }
    }
}