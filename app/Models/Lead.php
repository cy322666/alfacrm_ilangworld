<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $primaryKey = 'lead_id';
//    protected $keyType = 'string';
    //public $incrementing  = false;

    public $amoApi;

    public function __construct()
    {
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/resources/access/amocrm.php';

        $this->amoApi = \Ufee\Amo\Amoapi::setInstance([
            'id'     => $access['id'],
            'domain' => $access['subdomain'],
            'login'  => $access['login'],
            'hash'   => $access['api_key'],
        ]);
    }

    public function searchAmo(Customer $customer)
    {

    }

    public function createAmo(Customer $customer)
    {
        $contact = $this->amoApi
            ->contacts()
            ->find($customer->contact_id);
        $lead = $contact->createLead();
        //$lead->sale = $customer->;//как в альфе?
        $lead->save();

        return $lead;
    }

    public function updateAmo(Customer $customer)
    {
        $amo_lead = $this->amoApi
            ->leads()
            ->find($customer->id);

        $amo_lead->cf('Дата и время пробного')->setValue($customer->datetime_trial);
        $amo_lead->cf('Преподаватель')->setValue($customer->teacher);
        $amo_lead->cf('Вид обучения')->setValue($customer->method);
        $amo_lead->cf('Язык обучения')->setValue($customer->languange);

        $amo_lead->save();
    }

//    public function findContactArrayQuery($queryArray)
//    {
//        foreach ($queryArray as $query) {
//            if($query != '') {
//                $contact = $this->amoApi->Find('query', $query);        //какой метод
//                if($contact) return $contact;
//            }
//        }
//
//        return false;
//    }

    public function findLeadByQuery(string $key, $value)
    {

    }
}
