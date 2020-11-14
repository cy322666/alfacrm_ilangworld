<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'contact_id',
        'phone',
        'email',
        'name',
    ];
    //protected $primaryKey = 'contact_id';
    public $incrementing = false;

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
        if($customer->phone) {
            $contacts = $this->amoApi
                ->contacts()
                ->searchByPhone($customer->phone);
        }
        if($contacts && $customer->email) {
            if($customer->email) {
                $contacts = $this->amoApi
                    ->contacts()
                    ->searchByEmail($customer->email);
            }
        }
        if(!$contacts) return false;
            else return $contacts->first();
    }

    public function createAmo(Customer $customer)
    {
        $contact = $this->amoApi
            ->contacts()
            ->create();
        $contact->name = $customer->name;
        //$contact->attachTags(['Amoapi', 'Test']);
        if($customer->phone) $contact->cf('Телефон')->setValue($customer->phone, 'Home');
        if($customer->email) $contact->cf('Email')->setValue($customer->email);
        $contact->save();

        return $contact;
    }

    public function updateAmo($id, Customer $customer)
    {
        $contact = $this->amoApi
            ->contacts()
            ->find($id);
        //$lead->sale = $customer->;//как в альфе?
        if($customer->phone) $contact->cf('Телефон')->setValue($customer->phone, 'Home');
        if($customer->email) $contact->cf('Email')->setValue($customer->email);
        $contact->save();
    }
}
