<?php

declare(strict_types=1);
class ValidEmailValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        if (!(!empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false)) {
            return false;
        }
        if (!checkdnsrr(substr($value, strpos($value, '@') + 1), 'MX')) {
            return false;
        }
        return true; //!empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}