<?php

declare(strict_types=1);

class Validator extends AbstractValidator
{
    public function __construct(array $validator)
    {
        $this->validatorClass = $validator;
    }

    public function validate(array $items, Model $obj) : void
    {
        foreach ($items as $item => $rules) {
            $display = $rules['display'];
            $value = $item !== 'terms' ? $obj->getEntity()->{'get' . ucwords($item)}() : $this->getTerms($obj);
            if (isset($value)) {
                foreach ($rules as $rule => $rule_value) {
                    if ($rule === 'required' && (empty($value) || $value == '[]')) {
                        $this->ruleCheck($rule, $rule_value, $items, $item, $display, $obj);
                    } elseif ($value == 'terms') {
                    } elseif (!empty($value)) {
                        $this->ruleCheck($rule, $rule_value, $items, $item, $display, $obj);
                    }
                }
            }
        }
    }
}