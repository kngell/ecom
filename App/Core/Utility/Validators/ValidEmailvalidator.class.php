<?php

declare(strict_types=1);
class ValidEmailvalidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        return !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}