<?php

declare(strict_types=1);
class Uikit extends AbstractThemeLibrary
{
    public function theme(?string $wildcard = null): array
    {
        return [
            'form' => [
                'input' => 'uk-input',
                'checkbox' => 'uk-checkbox',
                'radio' => 'uk-radio',
                'textarea' => 'uk-textarea',
                'select' => 'uk-select',
                'range' => 'uk-range',
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