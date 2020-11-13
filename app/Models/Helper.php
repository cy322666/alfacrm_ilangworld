<?php

namespace App\Models;

class Helper
{
    public static function transformStatus($status_id)
    {
        $statuses = [
            123 => 321,
            456 => 654,
        ];

        foreach ($statuses as $amo_status => $alfa_status) {
            if($status_id == $amo_status)  return $alfa_status;
            if($status_id == $alfa_status) return $amo_status;
        }
    }

    public static function transformBranch($id)
    {
        $branches = [
            123 => 321,
            456 => 654,
        ];

        foreach ($branches as $amo_branch => $alfa_branch) {
            if($id == $amo_branch)  return $alfa_branch;
            if($id == $alfa_branch) return $amo_branch;
        }
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
