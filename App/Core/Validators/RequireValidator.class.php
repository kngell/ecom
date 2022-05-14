<?php

declare(strict_types=1);
class RequireValidator extends CustomValidator
{
    public function runValidation()
    {
        if ($this->getField() == 'terms') {
            if (( new ReflectionProperty($this->getModel()->getEntity(), $this->getModel()->getEntity()->getFields($this->getField())))->isInitialized($this->getModel()->getEntity())) {
                $value = $this->getModel()->getEntity()->{'get' . $this->getField()}();
            } else {
                $value = '';
            }
        } else {
            $value = $this->getModel()->getEntity()->{'get' . $this->getField()}();
        }

        return !(empty($value) || $value == '[]');
    }
}