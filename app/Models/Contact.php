<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//либа амо

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'phone',
        'email',
        'name',
    ];

    protected $primaryKey = 'contact_id';
    public $incrementing = false;

    public $amoApi;
//    protected $attributes = [
//        'delayed' => false,//по умолчанию
//    ];

    public function __construct()
    {
        //авторизация по апи
        $amo = \Ufee\Amo\Oauthapi::setInstance([
            'domain' => 'testdomain',
            'client_id' => 'b6cf0658-b19...', // id приложения
            'client_secret' => 'D546t4yRlOprfZ...',
            'redirect_uri' => 'https://site.ru/amocrm/oauth/redirect',
        ]);

        $amo = \Ufee\Amo\Oauthapi::getInstance('b6cf0658-b19...');
    }

    public function searchAmo($query)
    {

    }
}
