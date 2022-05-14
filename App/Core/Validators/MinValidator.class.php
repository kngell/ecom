<?php

declare(strict_types=1);
class MinValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        $pass = (strlen($value) >= $this->getRule());
        return $pass;
    }
}