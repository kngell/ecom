<?php

declare(strict_types=1);
class MatchesValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->{$this->getField()};

        return $value == $this->rule;
    }
}