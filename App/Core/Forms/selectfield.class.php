<?php

declare(strict_types=1);
class SelectField extends BaseField
{
    public function setType() : self
    {
        $this->type = '';
        return $this;
    }

    public function options()
    {
        return sprintf(
            '<option value="%s"> %s </option>',
            $this->model->{$this->attribute},
            current($this->model->get_countrie($this->model->{$this->attribute})) ?? ''
        );
    }

    public function renderField(): string
    {
        return sprintf(
            '<select name="%s" class="form-select %s %s" id="%s" autocomplete="nope" placeholder=" " %s>%s</select>',
            $this->attribute,
            $this->fieldclass ?? '',
            $this->hasErrors(),
            $this->fieldID ?? $this->attribute,
            $this->customAttribute,
            $this->options()
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
