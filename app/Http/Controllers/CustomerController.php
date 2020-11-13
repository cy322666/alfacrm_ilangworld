<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Lead;
//подключение альфалибы

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()//Request $request
    {
//        $action = $request->input('entity');
//
//        $this->$action($request);
    }

    public function create()//Request $request
    {
        $arr = [
            'name' => 'test',
            'branch_id' => '1',
            'entity_id' => 11111,
        ];

        $customer = Customer::create($arr);
    }

    public function update()
    {
        $lead = Lead::find(111222);

        dd($lead);
//        $customer = Customer::findOrCreate(['entity_id' => 11111]);
//        if(!$customer->lead_id) {
//            //поиск в бд
//            $lead = Lead::create();
//            $contact = Contact::findOrCreate(['phone' => '79996373955']);
//
//
//            $lead->save();
//
//            //находим и пишем обратно в бд
//            $customer->lead_id;
//            $customer->save();
//        } else {
//            Lead::CustomerUpdate($customer);
//        }

        //поиск в амо
        //создание или обновление
    }

    //
}
