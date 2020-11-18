<?php

namespace cy322666\AlfaCRM\Base;

class Curl
{
    public function __construct()
    {
    }

    public static function Query($link, $array)
    {
        $headers = self::getHeaders();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, 'https://welcometonordicworld.s20.online/v2api'.$link);
        if($array) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($array));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        }
        $Response = json_decode(curl_exec($curl), true);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl))
            throw new \Exception('Curl error');

        curl_close($curl);

        return $Response;
    }

    private static function getHeaders()
    {
        $token = self::getToken();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-ALFACRM-TOKEN: '.$token;

        return $headers;
    }

    private static function getToken()
    {
        $tokenPath = storage_path('token.txt');
        $token = file_get_contents($tokenPath);
        if($token) {
            return $token;
        } else {
            echo 'токен в файле не найден';
        }
    }

    private static function getResponse($link, $Response)
    {
        var_dump($link);
        switch ($link) {//200 /
            case '/auth/login':
                if($Response['token']) {
                    $Response = $Response['token'];
                } else {
                    $Response = false;
                }
                break;
            case '/v2api/branch/index':
                return $Response;
                break;
            case '/1/customer/index':
                if($Response['items'][0]) return $Response['items'][0];
                    else return false;
            case '/1/lead-source/index':
                if($Response['items'][0]) return $Response['items'];
                    else return false;
        }
        return $Response;
    }
}