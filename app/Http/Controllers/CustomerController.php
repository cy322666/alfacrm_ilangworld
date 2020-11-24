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

        $alfa_customer = $customer->alfaApi->Customer->findById($customer_id, 0);//0 - лид//1 - клиент;

        $customer->customer_id = $customer_id;
        if($alfa_customer['custom_datetime_trial'] != '') $customer->datetime_trial = $alfa_customer['custom_datetime_trial'];
        //$customer->branch_id   = $alfa_customer['branch_ids'][0];
        //$customer->study_status_id = $lead_status_id;
        //$customer->legal_name = $alfa_customer['legal_name'];
        //$customer->legal_type = $alfa_customer['legal_type'];
        $customer->phone = $alfa_customer['phone'][0];
        $customer->languange = $alfa_customer['custom_languange'];
        $customer->loyalty = $alfa_customer['custom_loyalty'];
        $customer->method = $alfa_customer['custom_method'];
        $customer->teacher = $alfa_customer['custom_teacher'];
        $customer->email = implode('', $alfa_customer['email']);
        $customer->status_id = $lead_status_id;
        $customer->save();

        $lead = Lead::find($customer->lead_id);//если пряморукое создание, если из альфы создается, то сложнее
        /*
         * обновление в таблице лида
         */
        $lead->updateAmo($customer);
        $lead->status_id = Helper::convertAlfaStatus($customer->status_id);
        $lead->save();

        $contact = Contact::find($lead->contact_id);
        $contact->updateAmo($customer);
        /*
         * обновление в таблице контакта
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
