<?php

declare(strict_types=1);

abstract class AbstractFormBuilder implements FormBuilderInterface
{
    /** @var array - constants which defines the various parts the form element */
    const FORM_PARTS = [
        'action' => '',
        'method' => 'post',
        'accept-charset' => '',
        'enctype' => 'application/x-www-form-urlencoded',
        'id' => '',
        'class' => ['uk-form-horizontal'],
        'rel' => '',
        'target' => '_self', /* defaults loads into itself */
        'novalidate' => true,
        'autocomplete' => 'nope',
        'leave_form_open' => false,
        //"onSubmit" => "UIkitNotify()"
    ];

    /** class constants for allowable field/input types */
    const SUPPORT_INPUT_TYPES = [
        'textarea',
        'select',
        'checkbox',
        'multiple_checkbox',
        'radio',
        'text',
        'range',
        'number',
        'datetime-local',
        'time',
        'date',
        'input',
        'password',
        'email',
        'color',
        'button',
        'reset',
        'submit',
        'tel',
        'search',
        'url',
        'file',
        'month',
        'week',
        'hidden',
    ];
    /** @var array */
    const HTML_ELEMENT_PARTS = [
        'wrapperClass' => ['input-box', 'mb-3'],
        'wrapperId' => '',
        'feedbackTag' => '<div class="invalid-feedback"></div>',
        'labelTag' => '%s {{label}}',
        'helpBlock' => '',
        'labelClass' => ['field_label'],
        'require' => '<span class="text-danger">*</span>',
        'spanClass' => ['span_class'],
        'labelId' => '',
        'before' => '',
        'after' => '',
        'element' => 'div',
        'element_class' => ['input-wrapper'],
        'element_id' => '',
        'element_style' => '',
    ];
    /** @var array */
    const FIELD_ARGS = [
        'before' => '<div class="input-box mb-3">',
        'after' => '</div>',
    ];

    /** @var array */
    protected array $inputs = [];
    /** @var array */
    protected array $formAttr = [];
    /** @var array */
    protected array $fieldWrapperClass = [];

    /**
     * Main class constructor.
     */
    public function __construct()
    {
    }

    /**
     * Set the form input attributes if any attribute if left empty
     * then it will use the default if any is set.
     *
     * @param string $key
     * @param $value
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        if (empty($key)) {
            throw new FormBuilderInvalidArgumentException('Invalid or empty attribute key. Ensure the key is present and valid');
        }
        switch ($key):
            case 'type':
                if (!in_array($value, self::SUPPORT_INPUT_TYPES)) {
                    throw new FormBuilderInvalidArgumentException('Unsupported object type ' . $value);
                }
        break;
        default:
                return false;
        break;
        endswitch;

        $this->inputs[$key] = $value;
        return true;
    }

    protected function class(string $str)
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->class($str);
        }
        return $this;
    }

    protected function placeholder(string $str) : self
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->attr($str);
        }
        return $this;
    }

    protected function label(string $str) : self
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->settings(['label' => $str, 'show_label' => true]);
        }
        return $this;
    }

    protected function labelClass(string $str) : self
    {
        if (count($this->inputObject) === 1) {
            $this->htmlAttr['labelClass'] = [$str];
        }
        return $this;
    }

    protected function req() : self
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->req();
        }
        return $this;
    }

    protected function spanClass(string $str) : self
    {
        if (count($this->inputObject) === 1) {
            $this->htmlAttr['spanClass'] = [$str];
        }
        return $this;
    }

    protected function labelUp(string $str) : self
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->settings(['label' => $str, 'show_label' => true, 'label_up' => true]);
        }
        return $this;
    }

    protected function id(string $id) : self
    {
        if (count($this->inputObject) === 1) {
            $this->inputObject[0]->id($id);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    protected function setAttributes(string $key, $value): bool
    {
        if (empty($key)) {
            throw new FormBuilderInvalidArgumentException('Invalid or empty attribute key. Ensure the key is present and valid');
        }
        switch ($key):
            case 'action':
                if (!is_string($key)) {
                    throw new FormBuilderInvalidArgumentException('Invalid action key. This must be a string.');
                }
        break;
        case 'method':
                if (!in_array($value, ['post', 'get', 'dialog'])) {
                    throw new FormBuilderInvalidArgumentException('Invalid form method. Either this is not set or you\'ve set an unsupported method type.');
                }
        break;
        case 'target' :
                if (!in_array($value, ['_self', '_blank', '_parent', '_top'])) {
                    throw new FormBuilderInvalidArgumentException('Invalid key');
                }
        break;
        case 'enctype':
                if (!in_array($value, ['application/x-www-form-urlencoded', 'multipart/form-data', 'text/plain'])) {
                    throw new FormBuilderInvalidArgumentException();
                }
        break;
        case 'id':
            case 'class':
                break;
        case 'novalidate':
            // case 'autocomplete' :
                if (!is_bool($value)) {
                    throw new FormBuilderInvalidArgumentException();
                }
        break;
        default:
                return false;
        break;
        endswitch;

        $this->formAttr[$key] = $value;
        return true;
    }
}