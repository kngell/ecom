<?php

declare(strict_types=1);

class SelectType extends AbstractAttr implements FormBuilderTypeInterface
{
    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = 'select';
    /** @var array - returns the combined attr options from extensions and constructor fields */
    protected array $attr = [];
    /** @var array - return an array of form fields attributes */
    protected array $fields = [];
    /** @var array returns an array of form settings */
    protected array $settings = [];
    /** @var mixed */
    protected mixed $options = null;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];

    /**
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
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

    /**
     * Returns an array of base options.
     *
     * @return array
     */
    public function getBaseOptions() : array
    {
        return [
            'name' => '',
            'id' => '',
            'class' => ['form-select'],
            'size' => '',
            'multiple' => false,
            'autocomplete' => 'off',
        ];
    }

    /**
     * Options which are defined for this object type
     * Pass the default array to the parent::configureOptions to merge together.
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

    /**
     * Publicize the default object type to other classes.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Publicize the default object options to the base class.
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->attr;
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper.
     *
     * @return array
     */
    public function getSettings() : array
    {
        $defaults = [
            'container' => true,
            'show_label' => true,
            'new_label' => '',
            'field_wrapper' => true,
            'input_wrapper' => false,
            'model_data' => false,
        ];
        return !empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults;
    }

    public function settings(array $settings) : self
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * The pre filter method provides a way to filtered the build field input
     * on a a per object type basis as all types share the same basic tags.
     *
     * there are cases where a tag is not required or valid within a
     * particular input/field. So we can filter it out here before being sent
     * back to the controller class
     *
     * @return mixed - return the filtered or unfiltered string
     */
    public function filtering(): string
    {
        if (!is_array($this->options)) {
            $this->options = [];
        }
        return $this->renderHtmlElement($this->attr);
    }

    /**
     * Render the form view to the builder method within the base class.
     *
     * @return string
     */
    public function view(): string
    {
        return sprintf('<select %s>%s</select>', $this->filtering(), $this->renderSelectOptions($this->options));
    }

    public function template() : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'SelectTemplate.php';
        $leblTemp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'inputLabelTemplate.php';
        if (file_exists($temp) && file_exists($leblTemp)) {
            return[
                file_get_contents($temp), file_get_contents($leblTemp),
            ];
        }
        return [];
    }
}