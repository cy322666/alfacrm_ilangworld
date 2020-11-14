<?php

namespace App\Models;

use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use cy322666\AlfaCRM\Api;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'entity_id',
    ];

    protected $primaryKey = 'entity_id';
    public $incrementing  = false;
    public $alfaApi;

    public function __construct()
    {
        require $_SERVER['DOCUMENT_ROOT'].'/alfacrm/app/Library/alfaCRM/Api.php';
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm/resources/access/alfacrm.php';

        $this->alfaApi = new Api($access);
    }

    public function searchAlfa(Lead $lead)
    {
//$this->alfaCRM->Customer->id = $leadDB['customer_id'];
//        $this->alfaCRM->Customer->name = $leadDB['fio'];
//        $this->alfaCRM->Customer->lead_source_id = $leadSourceId
        //     $this->alfaCRM->Customer->setBranchId($leadBranchId);
        //$result = $this->alfaCRM->Customer->update($leadDB['customer_id']);
    }

    public function createAlfa(Lead $lead)
    {

    }

    public function updateAlfa(Lead $lead)
    {

    }
}

