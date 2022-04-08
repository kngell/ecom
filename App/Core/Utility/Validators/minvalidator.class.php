<?php

declare(strict_types=1);
class Minvalidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->_model->getEntity()->{'get' . ucwords($this->field)}();
        $pass = (strlen($value) >= $this->rule);
        return $pass;
    }
}