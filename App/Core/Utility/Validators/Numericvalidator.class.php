<?php

declare(strict_types=1);
class Numericvalidator extends CustomValidator
{
    public function runValidation()
    {
        $pass = true;
        $value = $this->getModel()->{$this->getField()};
        if (!empty($value)) {
            $pass = is_numeric($value);
        }

        return $pass;
    }
}