<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Tariff;

class TariffController extends Controller
{
    public function create()//Request $request //переход в 142
    {
        $lead = json_decode(file_get_contents(storage_path('create_lead.txt')), true);
        //$_POST['add'] or ['status']

        $lead_id = $lead['status'][0]['id'] ?  $lead['status'][0]['id'] : $lead['add'][0]['id'];

        $lead = Lead::find($lead_id);

        $amo_lead = $lead->amoApi->leads()->find($lead_id);

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
        //dd($lead->customer_id);
        $alfa_customer = $customer->alfaApi->Customer->findById($lead->customer_id, 0);//0 - лид//1 - клиент;??

        $customer->setStudy(1);
        $customer->updateAlfa($lead, $contact);

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

    public function pay()//оплата у клиента в альфе
    {
        $arr = json_decode(file_get_contents(storage_path('tariff_pay.txt')), true);

        $tariff = new Tariff();
        $tariff->tariff_id = $arr['entity_id'];
        $tariff->customer_id = $arr['fields_new']['customer_id'];
        $tariff->income = $arr['fields_new']['income'];
        $tariff->pay_type_id = $arr['fields_new']['pay_type_id'];
        $tariff->save();

        $alfa_tariff = $tariff->alfaApi->Tariff;
        $alfa_tariffs = $alfa_tariff->findByCustomer($tariff->customer_id);

        if($alfa_tariffs) {
            foreach ($alfa_tariffs as $arrayTariff) {
                if($arrayTariff['e_date'] > date('d.m.Y')) {
                    //активный абон
                    $arrayAlfaTariff = $alfa_tariff->get($tariff->tariff_id);

                    $tariff->date_start = $arrayTariff['b_date'];
                    $tariff->date_finish = $arrayTariff['e_date'];
                    $tariff->count_lessons = $arrayAlfaTariff[0]['lessons_count'];

                    $tariff->save();
                }
            }
        }
    }

    public function lesson()//провели урок
    {
        $arr = json_decode(file_get_contents(storage_path('lesson.txt')), true);
//в хуке приходит массив с ид
        //проведенный
        /*
         * {
  "branch_id": 1,
  "event": "update",
  "entity": "Lesson",
  "entity_id": 4,
  "fields_old": {
    "status": 1,
    "group_ids": null
  },
  "fields_new": {
    "status": 3,//3 - проведен, 2 - отменен
    "group_ids": []
  },
  "fields_rel": [],
  "user_id": 2,
  "datetime": "2020-11-23 19:19:08"
}
         */
        $lesson_id = $arr['entity_id'];

        $tariff = new Tariff();
        $lesson = $tariff->alfaApi->Lesson;
        $lesson = $lesson->get($lesson_id);

        $tariff = Tariff::find(['customer_id' => $lesson['customer_ids'][0]]);

        if($lesson['status'] == 3 || $lesson['status'] == 2) {
            if($tariff->las_lessons) {
                $tariff->las_lessons = $tariff->last_lessons - 1;
            } else {
                $tariff->las_lessons = $tariff->count_lessons - 1;
            }
        }
        /*
         *   0 => array:16 [▼
    "id" => 3
    "branch_id" => 1
    "date" => "2020-11-20"
    "time_from" => "2020-11-20 14:00:01"
    "time_to" => "2020-11-20 15:00:00"
    "lesson_type_id" => 3
    "status" => 3
    "subject_id" => 8
    "room_id" => null
    "teacher_ids" => array:1 [▶]
    "customer_ids" => array:1 [▶]
    "group_ids" => []
    "streaming" => false
    "note" => ""
    "topic" => ""
    "details" => array:1 [▶]
  ]
]
         */
        dd($lesson);
        //находим в бд абон
        //минусуем
    }

    public function cron()//каждые 00:01 проверка 2 дня до конца абона
    {
        $tarrifs = Tariff::find(['last_lessons' => 2]);
        if($tarrifs != null) {
            foreach ($tarrifs as $tarrif) {
                //действия в амо
            }
        }
        //ищем в бд тех у кого через 2 урока
        //делаем штучки в амо
    }
}