<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;

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
            'phone' => 79996373955,
            'name' => 'test',
        ];

        /*
         * сперва делаем все в бд
         * для альфы тоже в бд
         * потом добиваем бд амо
         * обновляем в альфа
         * обновляем в амо
         */
        $contact = Contact::find($arr['contact_id']);
        if(empty($contact)) {
            $contact  = Contact::create($arr);
            $customer = Customer::create($arr);
            $alfa_customer = $customer->searchAlfa($contact);
            if ($alfa_customer) {
                //в альфа есть лид
                //смотрим статус?
//                $contact->customer_id;//id customer //надо ли???
//                $contact->save();
                $customer->contact_id;
                $customer->id;
                $customer->save();
            } else {
                //в альфа нет лида
            }
        } else {
            //контакт есть в бд
            $customer = Customer::find($contact->customer_id);
            $customer->update($arr);
            $customer->save();
            $customer->updateAlfa($contact);
            $contact->update($arr);
            $contact->save();
        }
        //теперь что касается сделки
        $lead = Lead::create($arr);
        $lead->contact_id = $contact->id;
        $lead->customer_id = $customer->id;
        $lead->save();

        if(!empty($customer->id)) {
            $alfa_customer = $customer->updateAlfa($lead);
        } else {
            $alfa_customer = $customer->createAlfa($lead);
            $lead->customer_id = $alfa_customer['id'];
            $lead->save();
        }
        $amo_lead = $lead->amoApi
            ->leads()
            ->find($lead->id);
        //$amo_lead->cf('Ссылка на лид в Альфа') = 'https://'.$customer->id;
        $amo_lead->save();

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
