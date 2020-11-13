<?php

namespace App\Models;

class Helper
{
    public static function transformStatus($status_id)
    {

    }

    public static function transformBranch($id)
    {

    }

    public static function clearPhone($phone)
    {
        $value = [ "(", ")", "-", "+", " ", ",","." ];
        $phone = str_replace($value, "", $phone);

        if (strlen($phone) <= 11 )
        {
            $phone = '7' . substr($phone, -10, 10);
        }
        return $phone;
    }
}
