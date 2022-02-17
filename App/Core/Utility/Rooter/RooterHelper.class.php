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

    public function formatQueryString($url)
    {
        if ($url != '') {
            $parts = explode('url', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return rtrim($url, '/');
    }
}