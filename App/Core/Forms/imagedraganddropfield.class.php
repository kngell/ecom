<?php

declare(strict_types=1);
class ImageDragAndDropField extends BaseField
{
    public function setType() : self
    {
        $this->type = '';
        return $this;
    }

    public function renderField(): string
    {
        return '%s';
    }

    public function FieldTemplate(): string
    {
        $template = file_get_contents(FILES . 'template' . DS . 'base' . DS . 'dragandDropTemplate.php');

        return $template;
    }
}
