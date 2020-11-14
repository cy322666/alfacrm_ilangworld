<?php

namespace App\Models;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        '',
        '',
        'customer_id',
    ];

    //protected $primaryKey = 'entity_id';
    public $incrementing  = false;
    public $alfaApi;

    public function searchAlfa(Lead $lead)
    {

    }

    public function createAlfa(Lead $lead)
    {

    }

    public function updateAlfa(Lead $lead)
    {

    }
}

