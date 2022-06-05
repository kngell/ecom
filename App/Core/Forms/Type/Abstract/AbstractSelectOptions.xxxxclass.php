<?php

declare(strict_types=1);
abstract class AbstractSelectOptions
{
    protected array $attr = [];
    protected array $globalAttr;

    protected function attrHtml() : string
    {
        $attribute = '';
        if (!empty($this->attr)) {
            foreach ($this->attr as $attr => $value) {
                if ($attr !== 'content') {
                    // $space = $attr !== array_key_first($this->attr) ? ' ' : '';
                    if (is_bool($value)) {
                        $attribute .= $value == true ? $attr : '';
                    } elseif (is_array($value) && !empty($value)) {
                        $attribute .= $attr . '=' . '"' . implode('', $value) . '"';
                    } else {
                        if (!empty($value)) {
                            $attribute .= $attr . '=' . $value;
                        }
                    }
                }
            }
            $attribute .= $this->getGlobalAttrHtml();
        }
        return $attribute;
    }

    protected function content() : string
    {
        return isset($this->attr['content']) ? $this->attr['content'] : '';
    }

    private function getGlobalAttrHtml() : string
    {
        $attribute = '';
        if (isset($this->globalAttr) && !empty($this->globalAttr)) {
            foreach ($this->globalAttr as $key => $value) {
                if (is_array($value) && !empty($value)) {
                    $attribute .= ' ' . $key . '=' . '"' . implode($value) . '"';
                } elseif (!empty($value)) {
                    $attribute .= $key . '=' . $value;
                }
            }
        }
        return $attribute;
    }
}