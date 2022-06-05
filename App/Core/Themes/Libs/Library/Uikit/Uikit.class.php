<?php

declare(strict_types=1);
class Uikit extends AbstractThemeLibrary
{
    public function theme(?string $wildcard = null): array
    {
        return [
            'form' => [
                'input' => 'form-control',
                'checkbox' => 'form-check-input',
                'radio' => 'form-check-input',
                'textarea' => 'form-control',
                'select' => 'form-select',
                'range' => 'form-range',
                'fieldset' => 'uk-fieldset',
                'legend' => 'uk-legend',
            ],
            'state_modifiers' => [
                'form-danger' => 'uk-form-danger',
                'form-success' => 'uk-form-success',
            ],
            'size_modifiers' => [
                'form-large' => 'uk-form-large',
                'form-small' => 'uk-form-small',
                'form-width-medium' => 'uk-form-width-medium',
                'form-width-xsmall' => 'uk-form-width-xsmall',
                'width-' . $wildcard => 'uk-form-width-' . $wildcard,
            ],
        ];
    }
}