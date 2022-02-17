<?php

declare(strict_types=1);
class ButtonField extends BaseField
{
    public string $type;

    public function setType() : self
    {
        $this->type = self::Type_BUTTON;
        $this->withWrapper = true;
        return $this;
    }

    public function renderField(): string
    {
        return sprintf(
            '<button type="%s" title="%s" class="%s" id="%s" %s>%s</button>',
            $this->type,
            $this->title,
            $this->fieldclass ?? '',
            !empty($this->fieldID) ? $this->fieldID : $this->attribute,
            $this->customAttribute ?? '',
            $this->tagText
        );
    }

    public function FieldTemplate(): string
    {
        $template = file_get_contents(FILES . 'template' . DS . 'base' . DS . 'forms' . DS . 'button_wrapper_template.php');
        $template = str_replace('{{classwrapper}}', $this->FieldwrapperClass ?? '', $template);
        $template = str_replace('{{button}}', '%s', $template);
        return $this->withWrapper ? $template : '%s';
    }
}
