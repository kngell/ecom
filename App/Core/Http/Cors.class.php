<?php

declare(strict_types=1);

class Cors extends GlobalVariables
{
    public function handle()
    {
        // array holding allowed Origin domains
        $allowedOrigins = [
            'https://localhost',
            'https://localhost:8001',
            'localhost:8001/favicon.ico',
            '/',
        ];
        $origin = $this->getServer('HTTP_ORIGIN');
        if (isset($origin) && $origin != '') {
            foreach ($allowedOrigins as $allowedOrigin) {
                if (preg_match('#' . $allowedOrigin . '#', $origin)) {
                    header('Access-Control-Allow-Origin: ' . $origin);
                    header('Access-Control-Allow-Credentials: true');
                    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
                    // header('Access-Control-Max-Age: 172800');
                    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
                    // header('Access-Control-Max-Age: 86400');
                    // header('Content-type: application/json; charset=UTF-8');
                    // header('Content-type: application/font-woff2; charset=UTF-8');
                    // header('Content-type: text/html; charset=UTF-8');
                    break;
                }
            }
            http_response_code(200);
        }
    }
}