<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\Contact;

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
            $contact = Contact::create($arr);
            $customer = Customer::create($arr);
            $alfa_customer = $customer->searchAlfa($contact);
            if ($alfa_customer) {
                $contact->update();//id customer
                $contact->save();
                $customer->update();//id contact_id and customer
                $customer->save();
            }
        } else {
            $contact->update($arr);
            $contact->save();
            $customer = Customer::find($contact->customer_id);
            $customer->update($arr);
            $customer->save();
            $customer->updateAlfa($contact);
        }
        //теперь что касается сделки
        $lead = Lead::create($arr);
        $lead->contact_id = $contact->id;
        $lead->customer_id = $customer->id;
        $lead->save();


//
//            if(empty($amo_contact->first())) {
//                $amo_contact = $contact->create();
//                $amo_contact->name = $arr['name'];
//                $amo_contact->phone = $arr['phone'];
//                $amo_contact->save();
//            } else {
//                $amo_contact = $amo_contact->first();
//
//                dd($amo_contact);
//            }
//        } else {
//            //контакт есть в бд
//            $contact = Contact::update($arr); //?
//        }
//
//        $customer = Customer::where(['lead_id' => $arr['id']]);
//
//        $alfa_customer = $customer->alfaApi->Customer();
//        $alfa_customer->searchAlfa($contact->phone);
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
