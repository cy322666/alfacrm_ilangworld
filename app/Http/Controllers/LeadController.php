<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class LeadController extends Controller
{
    public function create()//Request $request
    {
        $arr = [
            'id' => 4432322,
            //'cost' => 100,
            'status_id'   => 12323,
            'pipeline_id' => 11111,
            'contact_id'  => 11111,
        ];
        $lead = Lead::create($arr);
        /*
         * поиск существующего лида в альфе
         * если есть записываем
         * если есть связываем в бд с тем что в бд контакта
         * если нет то создание в бд
         * потом создание в альфе
         */
    }

    public function update()
    {
        $lead = Lead::find($arr['id']);
        if($lead) {
            /*
             * обновить сделку в бд
             * обновить и контакт
             * обновить клиента
             * обновить лид в бд
             * обновить лид в альфе
             */
        } else {
            $lead = Lead::create($arr);
            /*
             *
             *
             */
        }
    }
}
