<?php

declare(strict_types=1);

abstract class AbstractInputType
{
    public function getTemplate() : string
    {
        return $this->template;
    }

    public function getLabelTemplate() : string
    {
        return $this->labelTemplate;
    }

    public function settings(array $args) : self
    {
        foreach ($args as $key => $value) {
            $this->settings[$key] = $value;
        }
        return $this;
    }

    public function placeholder(string $str) : self
    {
        $this->attr[__FUNCTION__] = $str;
        return $this;
    }

    public function value(mixed $value) : self
    {
        $this->attr['value'] = $value;
        return $this;
    }

    public function Label(string $label) : self
    {
        $this->settings['show_label'] = true;
        $this->settings['label'] = $label;
        return $this;
    }

    public function class(string $str) : self
    {
        !in_array($str, $this->attr['class']) ? array_push($this->attr['class'], $str) : '';
        return $this;
    }

    public function req() : self
    {
        $this->attr['required'] = true;
        return $this;
    }

    public function id(string $id) : self
    {
        $this->attr['id'] = $id;
        return $this;
    }

    public function useModel(bool $useModel) : self
    {
        $this->settings['model_data'] = $useModel;
        return $this;
    }

    protected function mergeArys(array ...$params)
    {
        $class = [];
        foreach ($params as $paramsAry) {
            if (isset($paramsAry['class']) && is_array($paramsAry['class'])) {
                $class = array_merge_recursive($class, $paramsAry['class']);
            }
            if (isset($paramsAry['pattern']) && is_bool($paramsAry['pattern'])) {
                $patternBool = $paramsAry['pattern'];
            } elseif (isset($paramsAry['pattern']) && !is_bool($paramsAry['pattern'])) {
                $pattern = $paramsAry['pattern'];
            }
        }
        $arr1 = array_merge(...$params);
        if (isset($patternBool) && $patternBool == true) {
            if (isset($pattern)) {
                $arr1['pattern'] = $pattern;
            }
        } elseif (isset($patternBool) && $patternBool == false) {
            if (isset($pattern)) {
                unset($arr1['pattern']);
            }
        }
        $arr1['class'] = $class;
        return $arr1;
    }

    protected function template() : array
    {
        $temp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'InputFieldTemplate.php';
        $leblTemp = FILES . 'Template' . DS . 'Base' . DS . 'Forms' . DS . 'FieldsTemplate' . DS . 'inputLabelTemplate.php';
        if (file_exists($temp) && file_exists($leblTemp)) {
            return[
                file_get_contents($temp), file_get_contents($leblTemp),
            ];
        }
        return [];
    }
}