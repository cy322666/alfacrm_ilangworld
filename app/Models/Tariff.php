<?php

namespace App\Models;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Customer;
use cy322666\AlfaCRM\Api;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $primaryKey = 'tariff_id';
    public $incrementing  = false;
    public $alfaApi;

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/app/Library/alfaCRM/Api.php';
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/resources/access/alfacrm.php';

        $this->alfaApi = new Api($access);
    }

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

