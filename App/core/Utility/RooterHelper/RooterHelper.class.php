<?php

declare(strict_types=1);
class RooterHelper
{
    /**
     * Resolve
     * ==========================================================
     * Match route to routes in the rooting table and set params;.
     *
     * @param string $url
     * @param array $routes
     * @return array
     */
    public function resolve(string $url, array $routes) : array
    {
        foreach ($routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                return [$params, true];
            }
        }
        return [[], false];
    }

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
}
