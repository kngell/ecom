<?php

declare(strict_types=1);

trait ControllerTrait
{
    protected function isIncommingDataValid(ModelInterface $m, string $ruleMethod, array $newKeys = []) : void
    {
        method_exists('Form_rules', 'login') ? $m->validator(Form_rules::$ruleMethod()) : '';
        if (!$m->validationPasses()) {
            $this->jsonResponse(['result' => 'error-field', 'msg' => $m->getErrorMessages($newKeys)]);
        }
    }
}