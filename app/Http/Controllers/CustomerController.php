<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Helper;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Lead;

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
        $arr = json_decode(file_get_contents(storage_path('update_status_alfa.txt')), true);

        //dd($arr);
        $customer_id = $arr['entity_id'];
        $lead_status_id = $arr['fields_new']['lead_status_id'];

        $customer = Customer::find($customer_id);
        if(empty($customer)) $customer = new Customer();

        $alfa_customer = $customer->alfaApi->Customer;
        $alfa_customer->findById($customer_id, 0);//0 - лид//1 - клиент

        $customer->customer_id = $customer_id;
        $customer->branch_id   = $alfa_customer['branch_ids'][0];
        $customer->study_status_id = $lead_status_id;
        $customer->legal_name = $alfa_customer['legal_name'];
        $customer->legal_type = $alfa_customer['legal_type'];
        $customer->phone = $alfa_customer['phone'];
        $customer->datetime_trial = $alfa_customer['datetime_trial'];
        $customer->languange = $alfa_customer['languange'];
        $customer->loyalty = $alfa_customer['loyalty'];
        $customer->method = $alfa_customer['method'];
        $customer->teacher = $alfa_customer['teacher'];
        $customer->email = $alfa_customer['email'][0];
        $customer->save();

        $lead = Lead::find($customer->lead_id);//если пряморукое создание, если из альфы создается, то сложнее
        /*
         * обновление в таблице лида
         */
        $lead->status_id = Helper::convertAlfaStatus($customer->status_id);
        $lead->save();

        $lead->updateAmo($customer);

        $contact = Contact::find($lead->contact_id);
        $contact->updateAmo($customer);
        /*
         * обновление в таблице контакта
         */
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
