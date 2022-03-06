<?php

declare(strict_types=1);
class Form
{
    protected Model $model;
    protected Token $token;
    protected string $action = '';
    protected string $method = '';
    protected string $formID = '';
    protected string $formClass = '';
    protected string $formCustomAttr = '';
    protected array $fieldCommonclass = [];
    protected string $fieldWrapperClass = '';
    protected string $enctype = '';
    protected string $autocomplete = '';
    protected bool $alertErr = false;
    protected bool $nestField = false;
    protected array $inputHidden = [];
    protected string $btnID = '';

    public function __construct(private InputField $field, private TextareaField $textarea, private SelectField $select, private CheckBoxField $checkbox, private RadioField $radio, private ImageDragAndDropField $imageDD, private ButtonField $button)
    {
    }

    public function reset()
    {
        foreach ($this as $key => $value) {
            if (is_string($value)) {
                $this->{$key} = '';
            }
            if (is_bool($value)) {
                $this->{$key} = false;
            }
            if (is_array($value)) {
                $this->{$key} = [];
            }
        }
    }

    public function globalAttr(array $attrs = []) :self
    {
        $this->reset();
        foreach ($attrs as $key => $attr) {
            $this->{$key} = $attr;
        }
        return $this;
    }

    public function setModel(?Model $model = null) : self
    {
        if ($model != null) {
            $this->model = $model;
        }

        return $this;
    }

    public function wrapperClass(string $fieldWrapper) : self
    {
        $this->fieldWrapperClass = $fieldWrapper;

        return $this;
    }

    public function fieldCommonclass(array $fc) : self
    {
        $this->fieldCommonclass = $fc;

        return $this;
    }

    public function begin(string $alertid = '')
    {
        $id = $alertid != '' ? $alertid : 'alertErr';

        return sprintf('<form action ="%s" method="%s" class="%s" id="%s" enctype="%s" autocomplete="%s" %s> %s %s %s', $this->action, $this->method, $this->formClass, $this->formID, $this->enctype, $this->autocomplete, $this->formCustomAttr, isset($this->token) ? FH::csrfInput('csrftoken', $this->token->generate_token(8, $this->formID)) : '', $this->alertErr ? '<div id="' . $id . '"></div>' : '', $this->inputHidden());
    }

    public function btnId(string $bntID)
    {
        $this->btnID = $bntID;
        return $this;
    }

    public function submit(int $nb_Btn = 1, string $text = '')
    {
        $button = '';
        $submitType = 'submit';
        for ($i = 0; $i < $nb_Btn; $i++) {
            if ($i > 0) {
                $submitType = 'button';
            }
            if ($nb_Btn === 1) {
                $text = empty($text) ? 'Send' : $text;
            } else {
                $text = $i == 0 ? 'Cancel' : 'Send';
            }
            $id = empty($this->btnID) ? 'submitBtn' . $i : $this->btnID;
            $button .= '<div class="action"><button type="' . $submitType . '" name="submitBtn" id="' . $id . '" class="button">' . $text . '</button></div>';
        }

        return '<div class="mb-3">' . $button . '</div>';
    }

    public function end()
    {
        return '</form>';
    }

    public function button() : ButtonField
    {
        return $this->button
            ->setDefault()
            ->setType();
    }

    public function input(string $attribbute) : InputField
    {
        isset($this->model) ? $this->field->setModel($this->model) : '';

        return $this->field
            ->setDefault()
            ->nestField($this->formID, $this->nestField)
            ->setAttr($attribbute)
            ->setClass($this->fieldCommonclass)
            ->setFieldWrapperClass($this->fieldWrapperClass);
    }

    public function textarea(string $attribbute) : TextareaField
    {
        isset($this->model) ? $this->textarea->setModel($this->model) : '';

        return $this->textarea
            ->setDefault()
            ->nestField($this->formID, $this->nestField)
            ->setAttr($attribbute)
            ->setClass($this->fieldCommonclass)
            ->setFieldWrapperClass($this->fieldWrapperClass);
    }

    public function select(string $attribbute) : SelectField
    {
        isset($this->model) ? $this->select->setModel($this->model) : '';

        return $this->select
            ->setDefault()
            ->nestField($this->formID, $this->nestField)
            ->setAttr($attribbute)
            ->setClass($this->fieldCommonclass)
            ->setFieldWrapperClass($this->fieldWrapperClass);
    }

    public function checkbox(string $attribbute) : CheckBoxField
    {
        isset($this->model) ? $this->checkbox->setModel($this->model) : '';

        return $this->checkbox
            ->setDefault()
            ->nestField($this->formID, $this->nestField)
            ->setAttr($attribbute)
            ->setClass($this->fieldCommonclass)
            ->checkboxType()
            ->setFieldWrapperClass($this->fieldWrapperClass);
    }

    public function radio(string $attribbute) : RadioField
    {
        $this->model ? $this->radio->setModel($this->model) : '';

        return $this->radio
            ->setDefault()
            ->nestField($this->formID, $this->nestField)
            ->setAttr($attribbute)
            ->setClass($this->fieldCommonclass)
            ->setFieldWrapperClass($this->fieldWrapperClass);
    }

    public function imageDD() : ImageDragAndDropField
    {
        return $this->imageDD;
    }

    public function getRadio() : RadioField
    {
        return $this->radio;
    }

    public function getInput()
    {
        return $this->field;
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function getCheckbox()
    {
        return $this->checkbox;
    }

    public function getTexarea()
    {
        return $this->textarea;
    }

    public function getImageDD()
    {
        return $this->imageDD;
    }

    private function inputHidden()
    {
        $h_input = '';
        if (!empty($this->inputHidden)) {
            foreach ($this->inputHidden as $name => $value) {
                $attr = '';
                if (is_array($value)) {
                    foreach ($value as $key => $val) {
                        $attr .= $key . '="' . $val . '"';
                    }
                } else {
                    $attr = 'value = ' . $value;
                }
                $h_input .= '<input type="hidden" name="' . $name . '" ' . $attr . '>' . PHP_EOL;
            }
        }

        return $h_input;
    }
}