<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;

class TariffController extends Controller
{
    public function __construct()//Request $request
    {

    }

    public function create()//Request $request
    {
        $lead = json_decode(file_get_contents(storage_path('create_lead.txt')), true);
        //$_POST['add'] or ['status']
        $lead_id = $lead['status'][0]['id'] ?  $lead['status'][0]['id'] : $lead['add'][0]['id'];

        $amo_lead = $lead->amoApi->leads()->find($lead_id);

        $lead = Lead::find($lead_id);
        $lead->lead_id = $amo_lead->id;
        $lead->name    = $amo_lead->name;
        //$lead->sale    = $amo_lead->sale;
        $lead->contact_id  = $amo_lead->main_contact_id;
        $lead->status_id   = $amo_lead->status_id;
        $lead->pipeline_id = $amo_lead->pipeline_id;
        $lead->teacher   = $amo_lead->cf('Преподаватель')->getValue();
        $lead->method    = $amo_lead->cf('Вид обучения')->getValue();
        $lead->languange = $amo_lead->cf('Язык обучения')->getValue();
        $lead->os_learner = $amo_lead->cf('ОС от ученика')->getValue();
        $lead->os_teacher = $amo_lead->cf('ОС от преподавателя')->getValue();
        $lead->count_lessons = $amo_lead->cf('Кол-во занятий')->getValue();
        $lead->rate = $amo_lead->cf('Пакет')->getValue();
        $lead->count_mouth = $amo_lead->cf('Кол-во месяцев')->getValue();
        $lead->date_start  = $amo_lead->cf('Дата начала обучения')->getValue();
        $lead->date_finish = $amo_lead->cf('Дата окончания обучения')->getValue();
        $lead->save();

        $contact = Contact::find($lead->contact_id);
        $amo_contact = $contact->amoApi->contacts()->find($lead->contact_id);

        $contact->contact_id = $amo_contact->id;
        $contact->name       = $amo_contact->name;
        $contact->phone      = $amo_contact->cf('Телефон')->getValue();
        $contact->email      = $amo_contact->cf('Email')->getValue();
        $contact->loyalty    = $amo_contact->cf('Лояльность')->getValue();//Лояльность (контакт)
        $contact->sex        = $amo_contact->cf('Пол')->getValue();       //Пол (контакт)
        $contact->age        = $amo_contact->cf('Возраст')->getValue();   //Возраст (контакт)
        $contact->save();

        $customer = Customer::find($lead->customer_id);
        $alfa_customer = $customer->alfaApi->Customer->findById($customer->id, 0);//0 - лид//1 - клиент;
        $alfa_customer->setStudy(1);
        $alfa_customer->updateAmo($lead, $contact);

        $customer->customer_id = $alfa_customer['id'];
        $customer->contact_id = $contact->contact_id;
        $customer->lead_id    = $lead->lead_id;
        $customer->name       = $contact->name;
        $customer->phone      = $contact->phone;
        $customer->email      = $contact->email;
        $customer->study      = 1;
        $customer->loyalty    = $contact->loyalty;
        $customer->sex        = $contact->sex;
        $customer->age        = $contact->age;
        $customer->teacher    = $lead->teacher;
        $customer->method     = $lead->method;
        $customer->languange  = $lead->languange;
        $customer->os_learner = $lead->os_learner;
        $customer->os_teacher = $lead->os_teacher;
        $customer->count_lessons = $lead->count_lessons;
        $customer->rate = $lead->rate;
        $customer->count_mouth = $lead->count_mouth;
        $customer->date_start  = $lead->date_start;
        $customer->date_finish = $lead->date_finish;
        $customer->save();
    }

    public function pay()//присвоение
    {

    }
}