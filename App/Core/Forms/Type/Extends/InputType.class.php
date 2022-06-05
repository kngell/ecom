<?php

declare(strict_types=1);

class InputType extends AbstractAttr implements FormBuilderTypeInterface
{
    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = '';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];
    /** @var array - returns the combined attr options from extensions and constructor fields */
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

    public function __construct(array $fields, mixed $options = null, array $settings = [])
    {
        $this->fields = $this->filterArray($fields);
        $this->options = ($options != null) ? $options : null;
        $this->settings = $settings;
        if (is_array($this->baseOptions)) {
            $this->baseOptions = $this->getBaseOptions();
        }
        list($this->template, $this->labelTemplate) = $this->template();
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
                $this->attr = $this->mergeArys($defaultWithExtensionOptions, $this->fields, !empty($this->attr) ? $this->attr : []);
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
            'model_data' => false,
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
    public function view(?string $label = null, bool $modelData = false) : string
    {
        switch ($this->getType()) :
            case 'radio':
                return sprintf('%s', $this->filtering());
        break;
        case 'checkbox':
                return sprintf("\n<input %s>&nbsp;%s\n", $this->filtering(), (isset($this->settings['template']) && $this->settings['template'] == false ? $this->settings['checkbox_label'] : ''));
        break;
        case 'select':
            sprintf('<select %s>%s</select>', $this->filtering(), $this->renderSelectOptions($this->options, $modelData));
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
                return sprintf('<button type="%s" title="%s" class="%s" id="%s" %s>%s</button>', $this->attr['type'] ?? '', $this->attr['title'] ?? '', implode(' ', $this->attr['class']) ?? '', $this->attr['id'] ?? '', $this->attr['custom_attr'] ?? '', $label);
        break;
        default:
                return sprintf("\n<input %s>\n", $this->filtering());
        break;
        endswitch;
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
}