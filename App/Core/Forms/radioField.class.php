<?php

declare(strict_types=1);
class RadioField extends BaseField
{
    protected string $checked;
    protected string $price;

    public function setType() : self
    {
        $this->type = self::TYPE_RADIO;
        return $this;
    }

    public function radioType()
    {
        $this->type = self::TYPE_RADIO;

        return $this;
    }

    public function price($price) : self
    {
        $this->price = '<span class="price">' . $this->model->get_money()->getAmount($price) . '</span>';

        return $this;
    }

    public function checked(string $chk) : self
    {
        $this->checked = $chk;

        return $this;
    }

    public function renderField(): string
    {
        return sprintf(
            '<input type="%s" name="%s" value="%s" class="%s" id="%s" %s %s>',
            $this->type,
            $this->attribute ?? '',
            $this->fieldValue(),
            $this->fieldclass ?? '',
            $this->fieldID(),
            $this->customAttribute ?? '',
            $this->checked ?? ''
        );
    }

    public function FieldTemplate(): string
    {
        $template = file_get_contents(FILES . 'template' . DS . 'base' . DS . 'forms' . DS . 'inputradioTemplate.php');
        $template = str_replace('{{wrapperClass}}', $this->FieldwrapperClass ?? '', $template);
        $template = str_replace('{{labelClass}}', $this->labelClass ?? '', $template);
        $template = str_replace('{{inputID}}', $this->fieldID(), $template);
        $template = str_replace('{{spanClass}}', $this->spanClass ?? '', $template);
        $template = str_replace('{{labelTextClass}}', $this->labelTextClass ?? '', $template);
        $template = str_replace('{{label}}', $this->labelValue(), $template);
        $template = str_replace('{{labelDescr}}', $this->labelDescrValue(), $template);
        $template = str_replace('{{price}}', $this->price ?? '', $template);

        return $template;
    }
}
