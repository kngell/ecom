<?php

declare(strict_types=1);
class Alink extends AbstractAttr
{
    const OPTIONS_ATTR = [
        'href' => '#',
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

    public function href(string $str) : self
    {
        $this->attr[__FUNCTION__] = $str;
        return $this;
    }

    public function role(string $str) : self
    {
        $this->attr[__FUNCTION__] = $str;
        return $this;
    }
}