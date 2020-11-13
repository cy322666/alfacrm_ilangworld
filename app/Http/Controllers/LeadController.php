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
        $contact  = Contact::find($arr['contact_id'])->first;

        if(empty($contact)) {
            $amo_contact = $contact->amoApi->findByPhone();
            if(!$amo_contact) {
                $amo_contact = $contact->create();
                $amo_contact->name = '';
                $amo_contact->phone = '';
                $amo_contact->save();
            }
        }
        $customer = Customer::where(['lead_id' => $arr['id']]);

        $alfa_customer = $customer->alfaApi->Customer();
        $alfa_customer->searchAlfa($contact->phone);
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
        $arr = [
            'id' => 4432322,
            //'cost' => 100,
            'status_id'   => 12323,
            'pipeline_id' => 11111,
            'contact_id'  => 11111,
        ];

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
             * поискать контакт в бд
             * если нет создать, если есть обновить
             * создать лида альфа в бд
             * поискать лида в альфа
             * создать лида в альфа если нет
             * если есть подтянуть в бд
             *
             */
        }
    }
}
