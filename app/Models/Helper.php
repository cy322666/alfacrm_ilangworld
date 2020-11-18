<?php

namespace App\Models;

class Helper
{
    public static function getNameStatus($model, $status_id)
    {
        $pipelines = $model->amoApi
            ->account
            ->pipelines
            ->toArray();

        foreach ($pipelines as $pipeline_id => $pipeline) {
            if($pipeline_id == 3183190) {
                foreach ($pipeline['statuses']->toArray() as $status) {
                    if($status['id'] == $status_id) return $status['name'];
                }
            }
        }
    }

    public static function getIdStatusByName($alfaStatuses, $amoStatusName)
    {
        foreach ($alfaStatuses as $alfaStatus) {
            if($alfaStatus['name'] == $amoStatusName ) return $alfaStatus['id'];
        }
    }

    public static function clearPhone($phone)
    {
        $value = [ "(", ")", "-", "+", " ", ",","." ];
        $phone = str_replace($value, "", $phone);

        if (strlen($phone) <= 11 )
        {
            $phone = '+7' . substr($phone, -10, 10);
        }
        return $phone;
    }
}
