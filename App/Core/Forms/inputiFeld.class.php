<?php

declare(strict_types=1);
class InputField extends BaseField
{
    public string $type;

    public function setType() : self
    {
        $this->type = self::TYPE_TEXT;
        return $this;
    }

    public function passwordType()
    {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }

    public function emailType()
    {
        $this->type = self::TYPE_EMAIL;

        return $this;
    }

    public function withLabel() : self
    {
        $this->withLabel = true;

        return $this;
    }

    public function renderField(): string
    {
        return sprintf(
            '<input type="%s" name="%s" value="%s" class="form-control %s %s" id="%s" autocomplete="nope" placeholder=" " %s>',
            $this->type,
            $this->attribute,
            $this->fieldAttributeValue(),
            $this->fieldclass ?? '',
            $this->hasErrors(),
            !empty($this->fieldID) ? $this->fieldID : $this->attribute,
            $this->customAttribute
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
