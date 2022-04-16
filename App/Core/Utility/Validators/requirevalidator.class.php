<?php

declare(strict_types=1);
class Requirevalidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . $this->getField()}();
        return !(empty($value) || $value == '[]');
    }
}