<?php

declare(strict_types=1);

class InputType implements FormBuilderTypeInterface
{
    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = '';
    /** @var array - returns the combined attr options from extensions and constructor fields */
    protected array $attr = [];
    /** @var array - return an array of form fields attributes */
    protected mixed $fields;
    /** @var array returns an array of form settings */
    protected array $settings = [];
    /** @var mixed */
    protected mixed $options = null;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];
    /** @var string - this is the standard Template */
    protected string $template = '';
    /** @var string - this is the standard Label Template */
    protected string $labelTemplate = '';

    public function __construct()
    {
        list($this->template, $this->labelTemplate) = $this->template();
    }

    public function getTemplate() : string
    {
        return $this->template;
    }

    public function getLabelTemplate() : string
    {
        return $this->labelTemplate;
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
        $this->fields = $this->filterArray($fields);
        $this->options = ($options != null) ? $options : null;
        $this->settings = $settings;
        if (is_array($this->baseOptions)) {
            $this->baseOptions = $this->getBaseOptions();
        }
    }

    public function settings(array $args) : self
    {
        foreach ($args as $key => $value) {
            $this->settings[$key] = $value;
        }
        return $this;
    }

    public function attr(string $str) : self
    {
        $this->attr['placeholder'] = $str;
        return $this;
    }

    /**
     * Returns an array of base options.
     *
     * @return array
     */
    public function getBaseOptions() : array
    {
        return [
            'type' => $this->type,
            'name' => '',
            'id' => isset($this->attr['id']) ? $this->attr['id'] : ($this->fields['name'] ?? ''),
            'class' => ['form-control', 'input-box__input', isset($this->attr['id']) ? $this->attr['id'] : ($this->fields['name'] ?? '')],
            'checked' => false,
            'required' => false,
            'disabled' => false,
            'autofocus' => false,
            'autocomplete' => 'nope',
            'custom_attr' => '',
        ];
    }

    /**
     * @inheritdoc
     *
     * @param array $options
     * @return void
     */
    public function configureOptions(array $options = []) : void
    {
        if (empty($this->type)) {
            throw new FormBuilderInvalidArgumentException('Sorry please set the ' . $this->type . ' property in your extension class.');
        }
        if (!$this->buildExtensionObject()) {
            $defaultWithExtensionOptions = (!empty($options) ? array_merge($this->getBaseOptions(), $options) : $this->getBaseOptions());
            if ($this->fields) { /* field options set from the constructor */
                $this->throwExceptionOnBadInvalidKeys($this->fields, $defaultWithExtensionOptions, $this->buildExtensionName());

                /* Phew!! */
                /* Lets merge the options from the our extension with the fields options */
                /* assigned complete merge to $this->attr class property */
                $this->attr = array_merge($defaultWithExtensionOptions, $this->fields, !empty($this->attr) ? $this->attr : []);
            }
        }
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
        ];
        return !empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults;
    }

    public function filtering(): string
    {
        return $this->renderHtmlElement($this->attr, $this->options);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function view() : string
    {
        switch ($this->getType()) :
            case 'radio':
                return sprintf('%s', $this->filtering());
        break;
        case 'checkbox':
                return sprintf("\n<input %s>&nbsp;%s\n", $this->filtering(), (isset($this->settings['template']) && $this->settings['template'] == false ? $this->settings['checkbox_label'] : ''));
        break;
        case 'multiple_checkbox':
                if (
                    isset($this->options) &&
                    is_array($this->options) &&
                    count($this->options) > 0) {
                    foreach ($this->options['choices'] as $key => $option) {
                        return '<input type="checkbox" class="uk-checkbox" name="visibility[]" id="' . $key . '" value="' . $key . '">&nbsp;' . Stringify::capitalize($key);
                    }
                }
        break;
        case 'submit':
                return sprintf("\n<button %s>\n", $this->filtering());
        break;
        default:
                return sprintf("\n<input %s>\n", $this->filtering());
        break;
        endswitch;
    }

    public function Label(string $label) : self
    {
        $this->settings['show_label'] = true;
        $this->settings['label'] = $label;
        return $this;
    }

    public function class(string $str) : self
    {
        !in_array($str, $this->attr['class']) ? array_push($this->attr['class'], $str) : '';
        return $this;
    }

    public function req() : self
    {
        $this->attr['required'] = true;
        return $this;
    }

    public function id(string $id) : self
    {
        $this->attr['id'] = $id;
        return $this;
    }

    protected function template() : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'InputFieldTemplate.php';
        $leblTemp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'inputLabelTemplate.php';
        if (file_exists($temp) && file_exists($leblTemp)) {
            return[
                file_get_contents($temp), file_get_contents($leblTemp),
            ];
        }
        return [];
    }

    /**
     * Construct the name of the extension type using the upper camel case
     * naming convention. Extension type name i.e Text will also be suffix
     * with the string (Type) so becomes TextType.
     *
     * @return string
     */
    private function buildExtensionName() : string
    {
        $extensionName = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $this->type . 'Type'))));
        return ucwords($extensionName);
    }

    /**
     * Construct the extension namespace string. Extension name is captured from
     * the buildExtensionName() method name. Extension objects are also instantiated
     * from this method and check to ensure its implementing the correct interface
     * else will throw an invalid argument exception.
     *
     * @return void
     */
    private function buildExtensionObject() : void
    {
        $getExtensionNamespace = __NAMESPACE__ . '\\' . $this->buildExtensionName();
        $getExtensionObject = new $getExtensionNamespace($this->fields);
        if (!$getExtensionObject instanceof FormExtensionTypeInterface) {
            throw new FormBuilderInvalidArgumentException($this->buildExtensionName() . ' is not a valid form extension type object.');
        }
    }
}