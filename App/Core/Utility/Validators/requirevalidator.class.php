<?php

declare(strict_types=1);
class Requirevalidator extends CustomValidator
{
    public function runValidation()
    {
        $pass = true;
        $value = $this->_model->{$this->field};
        $pass = $value == '0' ? true : (!(empty($value) || $value == '[]'));

        return $pass;
    }
}
