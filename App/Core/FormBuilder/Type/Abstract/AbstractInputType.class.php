<?php

declare(strict_types=1);

abstract class AbstractInputType
{
    protected function mergeArys(array ...$params)
    {
        $class = [];
        foreach ($params as $paramsAry) {
            if (isset($paramsAry['class']) && is_array($paramsAry['class'])) {
                $class = array_merge_recursive($class, $paramsAry['class']);
            }
        }
        $arr1 = array_merge(...$params);
        $arr1['class'] = $class;
        return $arr1;
    }
}