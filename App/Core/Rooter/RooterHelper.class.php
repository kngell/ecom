<?php

declare(strict_types=1);
class RooterHelper
{
    /**
     * Trasnform Upper to Camel Case
     * ===========================================================.
     *
     * @param string $ctrlString
     * @return string
     */
    public function transformCtrlToCmCase(string $str) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * Transform Came Case
     * ===========================================================.
     * @param string $str
     * @return string
     */
    public function transformCmCase(string $str) : string
    {
        return \lcfirst($this->transformCtrlToCmCase($str));
    }

    public function formatUrlArguments(array $urlArgs)
    {
        $formatUrl = [];
        if (isset($urlArgs) && is_array($urlArgs) && !empty($urlArgs)) {
            foreach ($urlArgs as $key => $url) {
                if (str_contains($url, '=') && is_numeric($key)) {
                    $parts = explode('=', $url);
                    $formatUrl[$parts[0]] = $parts[1];
                }
            }
        }
        return !empty($formatUrl) ? $formatUrl : $urlArgs;
    }

    public function formatQueryString($parts)
    {
        if ($parts != '') {
            // $parts = explode('url', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return rtrim($url, '/');
    }

    public function dynamicNamespace(string $route, string $realroute, array $ctrlAry) : string
    {
        if (str_contains($route, 'controller')) {
            foreach ($ctrlAry as $namesapce => $ctrl) {
                if (in_array($realroute, $ctrl)) {
                    return $this->transformCtrlToCmCase($namesapce) . DS;
                    break;
                }
            }
        }
        return '';
    }
}