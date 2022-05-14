<?php

declare(strict_types=1);

abstract class AbstractValidator
{
    protected ContainerInterface $container;
    protected array $validatorClass;

    protected function ruleCheck(string $rule, mixed $rule_value, array $items, string $item, string $display, Model $obj)
    {
        if ($rule != 'display') {
            $obj->runValidation($this->container->make($this->validatorClass[$rule], [
                'model' => $obj,
                'field' => $item,
                'rule' => $rule_value,
                'msg' => $this->validatorMessages($display, $rule_value, $item, $items)[$rule],
            ]));
        }
    }

    protected function getTerms(Model $obj) : mixed
    {
        if (( new ReflectionProperty($obj->getEntity(), $obj->getEntity()->getFields('terms')))->isInitialized($obj->getEntity())) {
            return $obj->getEntity()->{'getTerms'}();
        }
        return '';
    }

    private function validatorMessages(string $display, mixed $rule_value, string $item, array $items) : array
    {
        $matchvalue = isset($items[$rule_value]['display']) ? $items[$rule_value]['display'] : '';
        return [
            'required' => ($item == 'terms') ? 'Please accept terms & conditions' : "{$display} is require",
            'min' => "{$display} must be a minimum of {$rule_value} characters",
            'max' => "{$display} must be a maximum of {$rule_value} caracters",
            'valid_email' => "{$display} is not valid Email",
            'is_numeric' => "{$display} has to be a number. Please use a numeric value",
            'matches' => "{$display} does not math {$matchvalue}",
            'unique' => "This {$display} already exist.",
            'Valid_string' => "{$display} is not valid",
            'Valid_password' => "{$display} is not valid Password",
            'Valid_domain' => "{$display} is does not have a valid Domain name!",
        ];
    }
}