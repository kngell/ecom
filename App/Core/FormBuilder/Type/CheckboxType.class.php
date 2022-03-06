<?php

declare(strict_types=1);

class CheckboxType extends InputType implements FormExtensionTypeInterface
{
    /** @var array */
    const DEFAULT_TEMPLATE_CLASS = [
        'wrapper_class' => ' input-box mb-3',
        'label_class' => 'input-box__label',
        'span_class' => 'ok',
    ];
    /** @var string - this is the text type extension */
    protected string $type = 'checkbox';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set Params.
     *
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
    public function setParams(array $fields, mixed $options = null, array $settings = [])
    {
        /* Assigned arguments to parent InputType constructor */
        parent::setParams($fields, $options, $settings);
        return $this;
    }

    public function template(array $args = []) : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'InputCheckboxTemplate.php';
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
     * @param array $options
     * @return void
     */
    public function configureOptions(array $extensionOptions = []): void
    {
        $this->defaults = [
            'class' => ['uk-checkbox'],
            'value' => '',
        ];
        parent::configureOptions($this->defaults);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getExtensionDefaults() : array
    {
        return $this->defaults;
    }

    /**
     * Publicize the default object options to the base class.
     *
     * @return array
     */
    public function getOptions() : array
    {
        return parent::getOptions();
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper.
     *
     * @return array
     */
    public function getSettings() : array
    {
        return parent::getSettings();
    }
}