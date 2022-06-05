<?php

declare(strict_types=1);

abstract class AbstractTextareaType
{
    protected string $template;
    protected string $labelTemplate;

    public function getTemplate() : string
    {
        return $this->template;
    }

    public function template() : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'SelectTemplate.php';
        $leblTemp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'inputLabelTemplate.php';
        if (file_exists($temp) && file_exists($leblTemp)) {
            return[
                file_get_contents($temp), file_get_contents($leblTemp),
            ];
        }
        return [];
    }

    public function placeholder(string $str) : self
    {
        $this->attr[__FUNCTION__] = $str;
        return $this;
    }

    public function class(string $str) : self
    {
        !in_array($str, $this->attr['class']) ? array_push($this->attr['class'], $str) : '';
        return $this;
    }

    public function settings(array $args) : self
    {
        foreach ($args as $key => $value) {
            $this->settings[$key] = $value;
        }
        return $this;
    }

    public function req() : self
    {
        $this->attr['required'] = true;
        return $this;
    }
}