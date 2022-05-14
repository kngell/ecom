<?php

declare(strict_types=1);
class MatchesValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        return $value == $this->getModel()->getEntity()->{'get' . ucwords($this->getRule())}();
    }
}