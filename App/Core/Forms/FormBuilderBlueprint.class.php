<?php

declare(strict_types=1);

class FormBuilderBlueprint implements FormBuilderBlueprintInterface
{
    public function text(string $name, array $class = [], mixed $value = null, bool $disabled = false, string|null $placeholder = null): array
    {
        return [
            TextType::class => current([
                array_merge($this->args($name, $class, $value, $placeholder), ['disabled' => $disabled]),
            ]),
        ];
    }

    public function number(string $name, array $class = [], mixed $value = null, bool $disabled = false, string|null $placeholder = null): array
    {
        return [
            NumberType::class => current([
                array_merge($this->args($name, $class, $value, $placeholder), ['disabled' => $disabled]),
            ]),
        ];
    }

    public function hidden(string $name, mixed $value = null, array $class = []): array
    {
        return [
            HiddenType::class => current([
                array_merge($this->arg($name, $class, $value), []),

            ]),
        ];
    }

    public function textarea(string $name, array $class = [], mixed $id = null, string|null $placeholder = null, int $rows = 5, int $cols = 33, ): array
    {
        return [
            TextareaType::class => current([
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'placeholder' => $placeholder,
                'rows' => $rows,
                'cols' => $cols,
            ]),
        ];
    }

    public function email(string $name, array $class = [], mixed $value = null, bool $required = false, bool $pattern = true, string|null $placeholder = null): array
    {
        return [
            EmailType::class => current([
                array_merge($this->args($name, $class, $value, $placeholder), ['required' => $required, 'pattern' => $pattern]),
            ]),
        ];
    }

    public function password(string $name, array $class = [], mixed $value = null, string|null $autocomplete = null, bool $required = false, bool $pattern = false, bool $disabled = false, string|null $placeholder = null): array
    {
        return [
            PasswordType::class => current([
                array_merge($this->args($name, $class, $value, $placeholder), ['autocomplete' => $autocomplete, 'required' => $required, 'pattern' => $pattern, 'disabled' => $disabled]),
            ]),
        ];
    }

    public function radio(string $name, array $class = [], mixed $value = null): array
    {
        return [
            RadioType::class => current([
                array_merge($this->arg($name, array_merge(['uk-radio'], $class), $value), []),
            ]),
        ];
    }

    public function checkbox(string $name, array $class = [], mixed $value = null): array
    {
        return [
            CheckboxType::class => current([
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value),
            ]),
        ];
    }

    public function select(string $name, array $class = [], ?string $id = null, mixed $size = null, bool $multiple = false, ): array
    {
        return [
            SelectType::class => current([
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'size' => $size,
                'multiple' => $multiple,
            ]),
        ];
    }

    public function multipleCheckbox(string $name, array $class = [], mixed $value = null): array
    {
        return [
            MultipleCheckboxType::class => current([
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value),
            ]),
        ];
    }

    public function submit(string $name, array $class = [], mixed $value = null): array
    {
        return [
            SubmitType::class => current([
                $this->arg($name, $class, $value),
            ]),
        ];
    }

    public function choices(array $choices, string|int $default = null, ?object $form = null): array
    {
        return [
            'choices' => $choices,
            'default' => ($default !== null) ? $default : 'pending',
            'object' => $form,
        ];
    }

    public function settings(bool $inlineFlipIcon = false, ?string $inlineIcon = null, bool $showLabel = true, ?string $newLabel = null, bool $wrapper = false, ?string $checkboxLabel = null, ?string $description = null): array
    {
        return [
            'inline_flip_icon' => $inlineFlipIcon,
            'inline_icon' => $inlineIcon,
            'show_label' => $showLabel,
            'new_label' => $newLabel,
            'before_after_wrapper' => $wrapper,
            'checkbox_label' => $checkboxLabel,
            'description' => $description,
        ];
    }

    private function args(string $name, array $class = [], mixed $value = null, string|null $placeholder = null): array
    {
        return [
            'name' => $name,
            'class' => array_merge([''], $class),
            'placeholder' => ($placeholder !== null) ? $placeholder : '',
            'value' => ($value !== null) ? $value : '',

        ];
    }

    private function arg(string $name, array $class = [], mixed $value = null): array
    {
        return [
            'name' => $name,
            'class' => $class,
            'value' => ($value !== null) ? $value : '',

        ];
    }
}