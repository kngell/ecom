<?php

declare(strict_types=1);
class Token extends RandomStringGenerator
{
    //=======================================================================
    //Generate Token
    //=======================================================================
    public function generate_token(int $length = 8, string $frm = '', string $alphabet = '')
    {
        $identifiant = '';
        $this->setAlphabet($alphabet);
        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $identifiant .= $this->alphabet[$randomKey];
        }
        $time = time();
        $separator = !empty($frm) ? $frm : '|';
        $hash = hash_hmac('sha256', session_id() . $identifiant . $time . $frm ?? '', CSRF_TOKEN_SECRET, true);

        return $this->urlSafeEncode($hash . $separator . $identifiant . $separator . $time);
    }

    //=======================================================================
    //Url Safe encode
    //=======================================================================
    public function urlSafeEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    //=======================================================================
    //Url Safe décode
    //=======================================================================
    public function urlSafeDecode($str)
    {
        return base64_decode(strtr($str, '-_', '+/'));
    }

    //=======================================================================
    //Validate token
    //=======================================================================
    public function validateToken($token = '', $frm = '')
    {
        $separator = !empty($frm) ? $frm : '|';
        $part = explode($separator, $this->urlSafeDecode($token));
        if (count($part) === 3) {
            $hash = hash_hmac('sha256', session_id() . $part[1] . $part[2] . $frm ?? '', CSRF_TOKEN_SECRET, true);
            if (hash_equals($hash, $part[0])) {
                return true;
            }
        }

        return false;
    }

    public function generate(int $length = 8, string $alphabet = '')
    {
        $token = '';
        $this->setAlphabet($alphabet);
        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }
        // Session::set(TOKEN_NAME, $token);
        return $token;
    }

    public static function check($formName, $token)
    {
        if (Session::exists(TOKEN_NAME) && $token === Session::get(TOKEN_NAME)) {
            Session::delete(TOKEN_NAME);

            return true;
        }
        $serverToken = hash_hmac('sha256', $formName, Session::exists(TOKEN_NAME) ? Session::get(TOKEN_NAME) : '');

        return hash_equals($serverToken, $token);
        //(Session::exists($token_name) && $token === Session::get($token_name));
    }
}
