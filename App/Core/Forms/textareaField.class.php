<?php

declare(strict_types=1);
class TextareaField extends BaseField
{
    protected int $row;

    public function setType() : self
    {
        $this->type = '';
        return $this;
    }

    public function row(int $r)
    {
        $this->row = $r;

        return $this;
    }

    public function renderField(): string
    {
        return sprintf(
            '<textarea name="%s" class="form-control %s %s" id="%s" row="%s" autocomplete="nope" %s>%s</textarea>',
            $this->attribute,
            $this->fieldclass ?? '',
            $this->hasErrors(),
            $this->fieldID ?? $this->attribute,
            $this->row ?? '',
            $this->customAttribute,
            $this->fieldAttributeValue(),
        );
    }

    public function FieldTemplate(): string
    {
        $template = file_get_contents(FILES . 'template' . DS . 'base' . DS . 'forms' . DS . 'inputfieldTemplate.php');
        $template = str_replace('{{classwrapper}}', $this->FieldwrapperClass ?? '', $template);
        $template = str_replace('{{feedback}}', $this->errors(), $template);
        $template = str_replace('{{labelTemp}}', !empty($this->labelUp) ? $this->labelUp : '%s {{label}}', $template);
        $template = str_replace('{{label}}', $this->withLabel ? $this->fieldLabelTemplate() : '', $template);

        return $template;
    }
}
