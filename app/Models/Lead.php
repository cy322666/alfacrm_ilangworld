<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'id',
        'pipeline_id',
        'status_id',
        'contact_id',
        //'cost',
    ];

    //protected $primaryKey = 'lead_id';
    public $incrementing  = false;
    public $amoApi;

    public function __construct()
    {
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm/resources/access/amocrm.php';

        $this->amoApi = \Ufee\Amo\Amoapi::setInstance([
            'id'     => $access['id'],
            'domain' => $access['subdomain'],
            'login'  => $access['login'],
            'hash'   => $access['api_key'],
        ]);
    }

    public function CustomerUpdate(Customer $customer)
    {

    }

    //или просто заменить на сами методы либы

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
