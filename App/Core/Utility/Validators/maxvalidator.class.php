<?php

declare(strict_types=1);
class Maxvalidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->{$this->getField()};
        $pass = (strlen($value) <= $this->rule);

        return $pass;
    }
}