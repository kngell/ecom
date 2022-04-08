<?php

declare(strict_types=1);
class MatchesValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->_model->{$this->field};

        return $value == $this->rule;
    }
}
