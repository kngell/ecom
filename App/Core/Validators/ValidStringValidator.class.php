<?php

declare(strict_types=1);
class ValidStringValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        return preg_match('/^[a-zA-Z- ]+$/', $value);
    }
}