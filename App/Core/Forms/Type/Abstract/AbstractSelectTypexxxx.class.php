<?php

declare(strict_types=1);

abstract class AbstractSelectType
{
    protected string $template;
    protected string $labelTemplate;

    public function getTemplate() : string
    {
        return $this->template;
    }

    public function class(string $str) : self
    {
        !in_array($str, $this->attr['class']) ? array_push($this->attr['class'], $str) : '';
        return $this;
    }
}