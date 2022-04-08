<?php

declare(strict_types=1);
class Numericvalidator extends CustomValidator
{
    public function runValidation()
    {
        $pass = true;
        $value = $this->_model->{$this->field};
        if (!empty($value)) {
            $pass = is_numeric($value);
        }

        return $pass;
    }
}
