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

        if(!$lead) $lead = new Lead();

        $amo_lead = $lead->amoApi->leads()->find($lead_id);

        $lead->lead_id = $amo_lead->id;
        $lead->name    = $amo_lead->name;
        //$lead->sale    = $amo_lead->sale;
        $lead->contact_id  = $amo_lead->main_contact_id;
        $lead->status_id   = $amo_lead->status_id;
        $lead->pipeline_id = $amo_lead->pipeline_id;
        $lead->datetime_trial = $amo_lead->cf('Дата и время пробного')->getValue();
        $lead->teacher   = $amo_lead->cf('Преподаватель')->getValue();
        $lead->method    = $amo_lead->cf('Вид обучения')->getValue();
        $lead->languange = $amo_lead->cf('Язык обучения')->getValue();
        $lead->save();

        if($lead->contact_id) $contact = Contact::find($lead->contact_id);
        if(empty($contact))   $contact = new Contact();

        $amo_contact = $contact->amoApi->contacts()->find($lead->contact_id);

        $contact->contact_id = $amo_contact->id;
        $contact->name       = $amo_contact->name;
        $contact->phone      = $amo_contact->cf('Телефон')->getValue();
        $contact->email      = $amo_contact->cf('Email')->getValue();
        $contact->loyalty    = $amo_contact->cf('Лояльность')->getValue();//Лояльность (контакт)
        $contact->sex        = $amo_contact->cf('Пол')->getValue();       //Пол (контакт)
        $contact->age        = $amo_contact->cf('Возраст')->getValue();   //Возраст (контакт)
        $contact->save();


        $customer = new Customer();

        $alfa_customer = $customer->searchAlfa($contact);//проверка статуса?//типа клиента?

        if(!$alfa_customer) $alfa_customer = $customer->createAlfa($lead, $contact);
        else {
            $alfa_customer->setStudy(0);
            $alfa_customer->updateAmo($lead, $contact);
        }

        $lead->customer_id = $alfa_customer['id'];
        $lead->save();

        $customer->customer_id = $alfa_customer['id'];
        $customer->status_id  = $alfa_customer['lead_status_id'];
        $customer->contact_id = $contact->contact_id;
        $customer->lead_id    = $lead->lead_id;
        $customer->name       = $contact->name;
        $customer->phone      = $contact->phone;
        $customer->study      = 0;
        $customer->email      = $contact->email;
        $customer->loyalty    = $contact->loyalty;//Лояльность (контакт)
        $customer->sex        = $contact->sex;    //Пол (контакт)
        $customer->age        = $contact->age;    //Возраст (контакт)
        $customer->datetime_trial = $lead->datetime_trial;
        $customer->teacher    = $lead->teacher;
        $customer->method     = $lead->method;
        $customer->languange  = $lead->languange;
        $customer->save();

        dd($alfa_customer);
        /*
         *
        "id" => 5
        "branch_ids" => array:1 [▶]
        "teacher_ids" => []
        "name" => "Вячеслав"
        "color" => null
        "is_study" => 0
        "study_status_id" => 1
        "lead_status_id" => 2
        "lead_reject_id" => null
        "lead_source_id" => null
        "assigned_id" => null
        "legal_type" => 1
        "legal_name" => "legal_name"
        "company_id" => null
        "dob" => ""
        "balance" => null
        "balance_base" => null
        "paid_count" => null
        "next_lesson_date" => null
        "paid_till" => null
        "last_attend_date" => null
        "b_date" => "2020-11-19 14:08:59"
        "e_date" => "2030-12-31"
        "note" => ""
        "paid_lesson_count" => null
        "paid_lesson_date" => null
        "phone" => array:1 [▶]
        "email" => array:1 [▶]
        "web" => []
        "addr" => []
         */
    }

    public function update()
    {
/*
 *         //теперь что касается сделки
        if($lead->customer_id) {
            //знаем ид лида в альфа
            //проверяем на активность??
            $customer = Customer::find($lead->customer_id);

            $alfa_customer = $customer->updateAlfa($lead, $contact);
            dd($alfa_customer);
            //вроде все
        } else {
 */

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
