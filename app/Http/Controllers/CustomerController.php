<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Lead;
//подключение альфалибы

class CustomerController extends Controller
{
    public function __construct()//Request $request
    {
//        $action = $request->input('entity');
//
//        $this->$action($request);
    }

    public function update_status()
    {
        $customer = json_decode(file_get_contents(storage_path('update_status_alfa.txt')), true);

        $customer_id = 3;
        $lead_status_id = 3;

        $customer = Customer::find($customer_id);
        $customer->status_id = $lead_status_id;

        $lead = Lead::find($customer->lead_id);
        //трансформируем статус
        //сохраняем
        //обновляем в амо

    }

    public function create()//Request $request
    {
        //скорее всего не понадобится

        $arr = [
            'name' => 'test',
            'branch_id' => '1',
            'entity_id' => 11111,
            'phone' => 79999999999,
        ];

        $customer = Customer::create($arr);


        $contact  = Contact::where(['phone' => $arr['phone']])->first;

        if(empty($contact->contact_id)) {
            $amo_contacts = $contact->amoApi->findByPhone($arr['phone']);
            if($amo_contacts->first()) {
                $amo_contact = $amo_contacts->first();
                $contact = Contact::createOrUpdate($amo_contact);
                $customer->update(['contact_id' => $amo_contact->phone]);
                $customer->save();
            } else {
                $amo_contact = $contact->amoApi->contacts();
                $amo_contact->name = '';
                $amo_contact->phone = '';
                $amo_contact->save();

                $contact->contact_id = $amo_contact->id;
                $contact->save();

                $leads = $amo_contact->leads();
                if(!empty($leads->first())) {
                    foreach ($leads->toArray() as $array_lead) {
                        if ($array_lead['status_id'] != 142 &&
                            $array_lead['status_id'] != 143) {

                            $amo_lead = $contact->amoApi->leads();
                            $amo_lead->find($array_lead['id']);

                            $lead = createOrUpdate($amo_lead);
                            $customer->lead_id = $amo_lead->id;
                            $customer->save();

                            break;
                        }
                    }
                    //если не нашли активную сделку
                    //создаем сделку из под контакта
                    $amo_lead = $amo_contact->create->leads();
                    $amo_lead->price = $customer->price;
                    $amo_lead->save();

                    $lead = createOrUpdate($amo_lead);
                    $customer->lead_id = $amo_lead->id;
                    $customer->save();

                    $alfa_customer = $customer->alfaApi->Customer();
                    $alfa_customer->lead_id = $amo_lead->id;
                    $alfa_customer->save();
                } else {
                    //создаем сделку из под контакта
                    $amo_lead = $amo_contact->create->leads();
                    $amo_lead->price = $customer->price;
                    $amo_lead->save();

                    $lead = createOrUpdate($amo_lead);
                    $customer->lead_id = $amo_lead->id;
                    $customer->save();

                    $alfa_customer = $customer->alfaApi->Customer();
                    $alfa_customer->lead_id = $amo_lead->id;
                    $alfa_customer->save();
                }
            }
        } else {
            //нет контакта в бд, ищем в амо и тд
            //создать контакт
            //...
        }
        /*
         * поискать контакт в бд
         * поискать контакт в амо
         * если нет создать если есть подтянуть в бд
         * если есть подтянуть в бд
         * обновить ее в амо
         */

    }

    public function update()
    {
        $arr = [
            'name' => 'test',
            'branch_id' => '1',
            'entity_id' => 11111,
        ];

        //тоже самое что и у лида

        $customer = Customer::find($arr);
        if($customer) {
            if($customer->lead_id) {
                //обновляем в амо контакт//а в бд контакт
                //обновляем в амо сделку и обновляем в бд
            }
        } else {
            $customer = Customer::create($arr);
            //поиск контакта в амо
            //проверка id в бд
            //если нет то добавляем и обновляем в амо
            //если есть обновляем в амо обновляем в бд
            //поиск в амо сделки (разные поиски))))
        }
        dd($customer);
    }
}
