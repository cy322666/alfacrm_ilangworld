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
            'status_id' => 12323,
            'pipeline_id' => 11111,
            'contact_id' => 11111,
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
        if (empty($contact)) {
            $contact = Contact::create($arr);
            $customer = Customer::create($arr);
            $alfa_customer = $customer->searchAlfa($contact);//проверка статуса?//типа клиента?
            if ($alfa_customer) {//в альфа есть лид//берем его инфу и заполняем в бд//смотрим статус?
                $customer->update($arr);
                $customer->contact_id;
                $customer->id;
                $customer->save();
            } else {//в альфа нет лида//создаем позже
            }
        } else {//контакт есть в бд
            $customer = Customer::find($contact->customer_id);
            $customer->update($arr);
            $customer->save();
            $customer->updateAlfa($contact);
            $contact->update($arr);
            $contact->save();
        }//теперь что касается сделки
        $lead = Lead::create($arr);
        $lead->contact_id = $contact->id;
        $lead->save();

        if (!empty($customer->id)) {
            $alfa_customer = $customer->updateAlfa($lead);
        } else {
            $alfa_customer = $customer->createAlfa($lead);
        }
        $lead->customer_id = $alfa_customer['id'];
        $lead->save();

        $amo_lead = $lead->amoApi
            ->leads()
            ->find($lead->id);
        //$amo_lead->cf('Ссылка на лид в Альфа') = 'https://'.$customer->id;
        $amo_lead->save();
    }

    public function update()
    {
        $arr = [
            'id' => 4432322,
            //'cost' => 100,
            'status_id' => 12323,
            'pipeline_id' => 11111,
            'contact_id' => 11111,
        ];

        $lead = Lead::find($arr['id']);
        if ($lead) {
            $lead->update($arr);
            $lead->save();
            $contact = Contact::find($lead->contact_id);
            $contact->update($arr);
            $contact->save();
            if ($lead->customer_id) {
                $customer = Customer::find($lead->customer_id);
                $customer->updateAlfa($lead);
                $customer->save();
            } else {
                $customer = Customer::create();
                $alfa_customer = $customer->searchAlfa($contact);//проверка статуса?//типа клиента?
                if ($alfa_customer) {//в альфа есть лид//берем его инфу и заполняем в бд//смотрим статус?
                    $customer->update($arr);
                    $customer->contact_id;
                    $customer->id;
                    $customer->save();
                } else {//в альфа нет лида//создаем позже
                    $alfa_customer = $customer->createAlfa($lead);
                    $lead->customer = $alfa_customer['id'];
                    $lead->save();
                    $amo_lead = $lead->AmoApi->leads()->find($lead->id);
                    //$amo_lead->cf(link customer)
                    $amo_lead->save();
                }
            }
        }
    }
}
