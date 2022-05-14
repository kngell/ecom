<?php

declare(strict_types=1);
class ValidDomainValidator extends CustomValidator
{
    public function runValidation()
    {
        $value = $this->getModel()->getEntity()->{'get' . ucwords($this->getField())}();
        return checkdnsrr(substr($value, strpos($value, '@') + 1), 'MX');
    }
}