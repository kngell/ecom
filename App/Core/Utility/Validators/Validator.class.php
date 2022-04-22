<?php

declare(strict_types=1);

class Validator
{
    private ContainerInterface $container;

    public function forms(array $items, ModelInterface $obj) : void
    {
        foreach ($items as $item => $rules) {
            $display = $rules['display'];
            $value = !in_array($item, ['terms', 'cpassword']) ? $obj->getEntity()->{'get' . ucwords($item)}() : $this->getSpecialValues($item, $obj);
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

    private function ruleCheck(string $rule, mixed $rule_value, array $items, string $item, string $display, ModelInterface $obj)
    {
        if ($rule != 'display') {
            $params = $this->validatorParams($display, $rule_value, $item, $items);
            $obj->runValidation($this->container->make($params[$rule]['class'], [
                'model' => $obj,
                'field' => $item,
                'rule' => $rule_value,
                'msg' => $params[$rule]['msg'],
            ]));
        }
    }

    private function getSpecialValues(string $item, ModelInterface $obj) : mixed
    {
        switch ($item) {
            case 'cpassword':
                return $obj->getEntity()->{'get' . ucfirst($obj->getMatchingTestColumn())}();
            break;
            case 'terms':
                if (( new ReflectionProperty($obj->getEntity(), $obj->getEntity()->getFields('terms')))->isInitialized($obj->getEntity())) {
                    return $obj->getEntity()->{'getTerms'}();
                }
                return '';
            break;

            default:
                // code...
                break;
        }
    }

    private function validatorParams(string $display, mixed $rule_value, string $item, array $items) : array
    {
        $matchvalue = isset($items[$rule_value]['display']) ? $items[$rule_value]['display'] : '';
        return [
            'required' => [
                'class' => Requirevalidator::class,
                'msg' => ($item == 'terms') ? 'Please accept terms & conditions' : "{$display} is require",
            ],
            'min' => [
                'class' => Minvalidator::class,
                'msg' => "{$display} must be a minimum of {$rule_value} characters",
            ],
            'max' => [
                'class' => Maxvalidator::class,
                'msg' => "{$display} must be a maximum of {$rule_value} caracters",
            ],
            'valid_email' => [
                'class' => ValidEmailvalidator::class,
                'msg' => "{$display} is not valid Email",
            ],
            'is_numeric' => [
                'class' => Numericvalidator::class,
                'msg' => "{$display} has to be a number. Please use a numeric value",
            ],
            'matches' => [
                'class' => MatchesValidator::class,
                'msg' => "{$display} does not math {$matchvalue}",
            ],
            'unique' => [
                'class' => UniqueValidator::class,
                'msg' => "This {$display} already exist.",
            ],
        ];
    }
}