<?php

declare(strict_types=1);

class ButtonType extends AbstractAttr implements FormBuilderTypeInterface
{
    use FormBuilderTrait;
    const OPTIONS_ATTR = [
        'type' => 'button',
        'id' => '',
        'class' => [],
        'content' => '',
    ];
    const HTML_ELEMENT_PARTS = [
        'wrapperClass' => ['button-box', 'mb-3'],
        'wrapperId' => '',
        'require' => '<span class="text-danger">*</span>',
        'element' => 'div',
        'element_class' => ['button-wrapper'],
        'element_id' => '',
        'element_style' => '',
    ];
    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = 'button';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];
    /** @var array - returns the combined attr options from extensions and constructor fields */
    protected mixed $fields;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];

    public function __construct(array $fields, mixed $options = null, array $settings = [])
    {
        $this->fields = $fields;
        $this->options = ($options != null) ? $options : null;
        $this->settings = $settings;
        if (is_array($this->baseOptions)) {
            $this->baseOptions = $this->getBaseOptions();
        }
        list($this->template, $this->labelTemplate) = $this->template();
    }

    public function __toString()
    {
        return sprintf('<button %s>%s</button>', $this->attrHtml(), $this->getContent()) . "\n";
    }

    public function getButton(array $globalAttr = []) : string
    {
        $this->globalAttr = $globalAttr;
        return $this->__toString();
    }

    public function htmlAttr() : array
    {
        $htmlArg = self::HTML_ELEMENT_PARTS !== null ? self::HTML_ELEMENT_PARTS : [];
        return $htmlArg;
    }

    /**
     * @inheritdoc
     *
     * @param array $options
     * @return void
     */
    public function configureOptions(array $options = []): void
    {
        $defaultWithExtensionOptions = (!empty($options) ? array_merge($this->baseOptions, $options) : $this->baseOptions);
        if ($this->fields) {
            $this->throwExceptionOnBadInvalidKeys($this->fields, $defaultWithExtensionOptions, __CLASS__);

            $this->attr = array_merge($defaultWithExtensionOptions, $this->fields);
        }
    }

    public function getBaseOptions() : array
    {
        return [
            'type' => $this->type,
            'name' => '',
            'id' => isset($this->attr['id']) ? $this->attr['id'] : ($this->fields['name'] ?? ''),
            'class' => ['form-control', isset($this->attr['id']) ? $this->attr['id'] : ($this->fields['name'] ?? '')],
        ];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->attr;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getSettings() : array
    {
        $defaults = [
            'field_wrapper' => true,
            'container' => true,
            'input_wrapper' => false,
            'show_label' => true,
            'label_up' => false,
            'label' => '',
            'require' => false,
            'model_data' => false,
        ];
        return !empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults;
    }

    public function filtering(): string
    {
        return $this->renderHtmlElement($this->attr, $this->options);
    }

    public function type(string $str) : self
    {
        $this->attr[__FUNCTION__] = $str;
        return $this;
    }

    public function template() : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'buttonTemplate.php';
        if (file_exists($temp)) {
            return[
                file_get_contents($temp), '',
            ];
        }
        return [];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function view(?string $label = null, bool $modelData = false) : string
    {
        return $this->getButton();
    }
}