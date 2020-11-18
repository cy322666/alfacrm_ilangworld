<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;

class LeadController extends Controller
{
    public function create()//Request $request
    {
        $lead = json_decode(file_get_contents(storage_path('create_lead.txt')), true);
        //$_POST['add'] or ['status']
        $lead_id = $lead['status'][0]['id'] ?  $lead['status'][0]['id'] : $lead['add'][0]['id'];

        $lead = Lead::find($lead_id);
        //3183190
//        if(!$lead) $lead = new Lead();
//
//        $amo_lead = $lead->amoApi->leads()->find($lead_id);
//
//        $lead->lead_id     = $amo_lead->id;
//        $lead->name        = $amo_lead->name;
//        $lead->sale        = $amo_lead->sale;
//        $lead->contact_id  = $amo_lead->main_contact_id;
//        $lead->status_id   = $amo_lead->status_id;
//        $lead->pipeline_id = $amo_lead->pipeline_id;
//        $lead->save();

          if($lead->contact_id) $contact = Contact::find($lead->contact_id);
          if(!$contact) $contact = new Contact();

        $amo_contact = $contact->amoApi->contacts()->find($lead->contact_id);

        $contact->contact_id = $amo_contact->id;
        $contact->name       = $amo_contact->name;
        //$contact->phone      = $amo_contact->phone;
        $contact->save();
//        dd($contact);

        //теперь что касается сделки
        if($lead->customer_id) {
            //знаем ид лида в альфа
            //проверяем на активность??
            $customer = Customer::find($lead->customer_id);

            $alfa_customer = $customer->updateAlfa($lead, $contact);
            //вроде все
        } else {
            $customer = new Customer();

            $alfa_customer = $customer->searchAlfa($lead, $contact);//проверка статуса?//типа клиента?
            if(!$alfa_customer) {
                $alfa_customer = $customer->createAlfa($lead);
            }
            $lead->customer_id = $alfa_customer['id'];
            $lead->save();
        }

        //$customer->customer_id = $alfa_customer['id'];
        //$customer->status_id = $alfa_customer['status_id'];

        //$amo_lead->cf('Ссылка на лид в Альфа') = 'https://'.$customer->id;
        //$amo_lead->save();
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
