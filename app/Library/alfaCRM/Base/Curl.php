<?php

namespace cy322666\AlfaCRM\Base;

class Curl
{
    public function __construct()
    {
        echo '>Curl';
    }

    public static function Query($link, $array)
    {
        $headers = self::getHeaders();

        echo '<pre>'; print_r('https://shkolaindigo2.s20.online/v2api'.$link); echo '</pre>';
        echo '<pre>'; print_r($array); echo '</pre>';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, 'https://shkolaindigo2.s20.online/v2api'.$link);
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

        if ($code !== 200) {
            echo '<pre>'; var_dump($Response); echo '</pre>';
            echo 'no 200';
        } else {
            $Response = self::getResponse($link, $Response);
        }
        return $Response;
    }

    private static function getHeaders()
    {
        $token = self::getToken();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-ALFACRM-TOKEN: '.$token;

        echo '<pre>'; print_r($headers); echo '</pre>';

        return $headers;
    }

    private static function getToken()
    {
        $token = file_get_contents('token.txt');
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