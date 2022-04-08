<?php

declare(strict_types=1);
class ValidEmailvalidator extends CustomValidator
{
    public function runValidation()
    {
        $pass = true;
        $value = $this->_model->getEntity()->{'get' . ucwords($this->field)}();
        if (!empty($value)) {
            $pass = filter_var($value, FILTER_VALIDATE_EMAIL);
        }
        return $pass;
    }
}