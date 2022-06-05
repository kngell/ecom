<?php

declare(strict_types=1);

trait FormBuilderTrait
{
    public function autoFigureNameFromInt(mixed $value)
    {
    }

    public function filterArray(array $fields): mixed
    {
        $v = [];
        if (is_array($fields)) {
            foreach ($fields as $key => $value) {
                $v[$key] = $value;
            }
        }
        return $v;
    }

    /**
     * Render the HTML input tags. the first argument accepts the key value pair
     * html attribute. and the second argument is of a mixed data type define
     * within the controller. ie. can be the content for the textarea or
     * choices array of looping select, radio or multi-checkbox options.
     *
     * @param array $attr
     * @param mixed|null $options
     * @return string
     */
    protected function renderHtmlElement(array $attr, mixed $options = null): string
    {
        $val = '';
        if (is_array($attr) && count($attr) > 0) {
            foreach ($attr as $key => $value) {
                if ($key != '') {
                    if ($value !== '') {
                        if (is_bool($value)) {
                            $val .= ($value === true) ? $key . ' ' : false;
                        } else {
                            $val .= $key . '="' . (is_array($value) ? implode(' ', $value) : $value) . '" '; /* Leave the space in */
                        }
                    }
                }
            }
            $val = substr_replace($val, '', -1);
            if (count($attr) > 0) {
                return $val;
            }
        }
        return '';
    }

    protected function renderSelectOptions(array $options, bool $useModel = false): bool|string
    {
        $output = '';
        if (is_array($options) && count($options) > 0) {
            foreach ($options as $key => $options) {
                $class = $options->getOptionGlobalAttr();
                foreach ($options->getOptions() as $k => $option) {
                    $output .= $option->getOption($class);
                }
            }
        }
        return $output;
    }

    /**
     * @param array $options
     * @param bool $useModel
     * @return bool|string
     */
    protected function renderSelectOptionsOld(array $options, bool $useModel = false): bool|string
    {
        $output = '';
        if (is_array($options) && count($options) > 0) {
            foreach ($options['choices'] as $key => $choice) {
                if ($choice == $key && $key != '') {
                    $selected = ' selected';
                    $disabled = ' disabled';
                } elseif (isset($options['default']) && $options['default'] !== null && $options['default'] == $choice) {
                    $selected = ' selected';
                    $disabled = ' disabled';
                } elseif (isset($options['default']) && is_array($options['default'])) {
                    $selected = (in_array($choice, $options['default']) ? ' selected' : '');
                    $disabled = (in_array($choice, $options['default']) ? ' disabled' : '');
                } else {
                    $selected = '';
                    $disabled = '';
                }
                $output .= '<option' . $disabled . ' value="' . $key . '"' . $selected . '>' . ($useModel ? $this->getNameFromID($choice, $options['object'] ?? '') : $choice) . '' . $selected . '</option>' . "\n";
            }
            return $output;
        }
        return false;
    }

    /**
     * @param array $attr
     * @param null $options
     * @return bool|string
     */
    protected function renderInputOptions(array $attr, $options = null, mixed $displayLabel = null): bool|string
    {
        if (!is_array($options)) {
            $options = [];
        }
        $val = '';
        if (is_array($attr) && count($attr) > 0) {
            foreach ($options['choices'] as $choices) {
                foreach ($choices as $key => $value) {
                    $checked = '';
                    if ($attr['value'] == $key) {
                        $checked = ' checked';
                    } elseif (isset($options['default']) && $options['default'] != null && $options['default'] == $key) {
                        $checked = ' checked';
                    } else {
                        $checked = '';
                    }
                    $val .= '<input type="' . $attr['type'] . '" name="' . $attr['name'] . '" id="' . $attr['id'] . '_' . $value . '" class="' . implode(' ', $attr['class']) . '" value="' . (is_string($value) ? strtolower($value) : $value) . '"' . $checked . '>' . ' ' . (is_int($value) ? $this->autoFigureNameFromInt($value) : $key) . "\n<br>";
                }
            }
            return $val;
        }
        return false;
    }

    /**
     * this method will automatically try and fetch the ID from the HTML input
     * its associated with to the populate its for="" tag. it will also use
     * the name tag from the input as the title for the label.
     *
     * @param array $objectTypeOptions
     * @param string|null $class
     * @param string|null $label
     * @return string
     */
    protected function formLabel(array $objectTypeOptions, ?string $class = null, ?string $label = null) : string
    {
        $output = '';
        if ($objectTypeOptions == null) {
            throw new FormBuilderInvalidArgumentException();
        }
        $output .= "\n<label";
        $output .= (!empty($objectTypeOptions['id']) ? ' for="' . $objectTypeOptions['id'] . '"' : '');
        $output .= (!empty($class) ? ' class="' . $class . '"' : ' form-label input-box__label"');
        $output .= '>';

        if ($label == '') {
            $output .= (!empty($objectTypeOptions['name']) ? str_replace(['_', '-'], ' ', htmlspecialchars(ucwords($objectTypeOptions['name']))) : '');
        } else {
            $output .= $label;
        }
        $output .= "</label>\n";

        return $output;
    }

    protected function buildTemplate(Object $objectType, array $htmlAttr, bool $label_up) : string
    {
        $template = $objectType->getTemplate();
        if (!empty($htmlAttr)) {
            foreach ($htmlAttr as $key => $value) {
                if (str_contains($template, $key)) {
                    if (is_array($value)) {
                        $template = str_replace('{{' . $key . '}}', implode(' ', $value), $template);
                    } elseif ($key == 'labelTag') {
                        if ($label_up) {
                            $htmlAttr['labelTag'] = '{{label}} %s';
                        }
                        $template = str_replace('{{labelTag}}', $htmlAttr['labelTag'], $template);
                    } else {
                        $template = str_replace('{{' . $key . '}}', $value, $template);
                    }
                }
            }
            return $template;
        }
    }

    protected function buildLabel(Object $objectType, string $template, array $htmlAttr, string $label, bool $show_label) : string
    {
        $options = $objectType->getOptions();
        if ($template != '') {
            $template = $this->getLabel($objectType, $template, $show_label);
            $template = str_replace('{{inputID}}', $options['id'] ?? '', $template);
            $template = str_replace('{{labelClass}}', implode(' ', $htmlAttr['labelClass']) ?? '', $template);
            $template = str_replace('{{spanClass}}', implode(' ', $htmlAttr['spanClass']), $template);
            $template = str_replace('{{label}}', $show_label ? $label : '', $template);
            $template = str_replace('{{req}}', isset($options['required']) ? $htmlAttr['require'] : '', $template);
            return $template;
        }
        return '';
    }

    /**
     * Throw an out of bound exception if we are passing a key which isn't part of the object
     * type default options.
     *
     * @param array $fields - array option from controller
     * @param array $extensionOptions
     * @param string $extensionObjectName - the name of the object the exception been thrown in
     * @return void
     */
    protected function throwExceptionOnBadInvalidKeys(array $fields, array $extensionOptions, string $extensionObjectName)
    {
        foreach (array_keys($fields) as $index) {
            if (!in_array($index, array_keys($extensionOptions), true)) {
                throw new FormBuilderOutOfBoundsException('One or more key [' . $index . "] is not a valid key for the object type $extensionObjectName");
            }
        }
    }

    private function getLabel(Object $objectType, string $template, $show_label) : string
    {
        if (in_array($objectType::class, ['CheckboxType'])) {
            return $template;
        }
        if ($show_label && !in_array($objectType::class, ['SelectType'])) {
            return str_replace('{{label}}', $objectType->getLabelTemplate(), $template);
        } else {
            return str_replace('{{label}}', '', $template);
        }
    }

    private function getNameFromID(int $id, object $form)
    {
        if (isset($form)) {
            return $form->getModel()->getNameForSelectField($id);
        }
    }
}