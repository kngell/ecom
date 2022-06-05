<?php

declare(strict_types=1);
class Option extends AbstractAttr
{
    const OPTIONS_ATTR = [
        'selected' => false,
        'disable' => false,
        'value' => '',
        'id' => '',
        'class' => [],
        'content' => '',
    ];

    public function __construct(array $args = [])
    {
        $this->attr = array_merge(self::OPTIONS_ATTR, $args, $this->attr);
    }

    public function __toString()
    {
        return sprintf('<option %s>%s</option>', $this->attrHtml(), $this->getContent()) . "\n";
    }

    public function getOption(array $globalAttr = []) : string
    {
        $this->globalAttr = $globalAttr;
        return $this->__toString();
    }

    public function selected(bool $selected) : self
    {
        $this->attr['selected'] = $selected;
        return $this;
    }

    public function disable(bool $disable) : self
    {
        $this->attr['disable'] = $disable;
        return $this;
    }
}