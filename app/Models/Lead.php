<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'lead_id',
        'pipeline_id',
        'status_id',
        'contact_id',
        //'cost',
    ];
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

    public function updateAmo($id, Customer $customer)
    {
        $lead = $this->amoApi
            ->leads()
            ->find($id);
        //$lead->sale = $customer->;//как в альфе?
        $lead->save();

        return $lead;
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
