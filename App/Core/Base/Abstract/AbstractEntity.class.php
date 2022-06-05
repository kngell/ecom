<?php

declare(strict_types=1);

abstract class AbstractEntity
{
    protected ReflectionClass $reflect;

    public function regenerateField(string $fieldName) : string
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $fieldName))));
    }

    public function getSetter(string $fieldName)
    {
        return 'set' . ucfirst($this->regenerateField($fieldName));
    }

    public function getGetters(string $fieldName)
    {
        return 'get' . ucfirst($this->regenerateField($fieldName));
    }

    protected function getOriginalField(mixed $field)
    {
        return strtolower($this->CamelCaseToUnderscore($field));
    }

    protected function filterPropertyComment(false|string $comment) : string
    {
        if (is_string($comment)) {
            preg_match('/@(?<content>.+)/i', $comment, $content);
            $content = isset($content['content']) ? $content['content'] : '';
            return trim(str_replace('*/', '', $content));
        }
        return '';
    }

    protected function reflectionInstance()
    {
        if (!isset($this->reflect)) {
            return $this->reflect = new ReflectionClass($this::class);
        }
        return $this->reflect;
    }

    private function CamelCaseToSeparator($value, $separator = ' ')
    {
        if (!is_scalar($value) && !is_array($value)) {
            return $value;
        }
        if (defined('PREG_BAD_UTF8_OFFSET_ERROR') && preg_match('/\pL/u', 'a') == 1) {
            $pattern = ['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'];
            $replacement = [$separator . '\1', $separator . '\1'];
        } else {
            $pattern = ['#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#'];
            $replacement = ['\1' . $separator . '\2', $separator . '\1'];
        }
        return preg_replace($pattern, $replacement, $value);
    }

    private function CamelCaseToUnderscore($value)
    {
        return $this->CamelCaseToSeparator($value, '_');
    }

    private function CamelCaseToDash($value)
    {
        return $this->CamelCaseToSeparator($value, '-');
    }
}