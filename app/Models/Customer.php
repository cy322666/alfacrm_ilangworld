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
    //public $incrementing  = false;
    //branch = 1
    public $alfaApi;

    public function __construct()
    {
        require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/app/Library/alfaCRM/Api.php';
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/resources/access/alfacrm.php';

        $this->alfaApi = new Api($access);
    }

    public function searchAlfa(Lead $lead, Contact $contact)
    {
        $alfa_customer = false;
        $customer    = $this->alfaApi->Customer;
        $allStatuses = $this->alfaApi->LeadStatus->getAll();

        $statusAmoName = Helper::getNameStatus($contact, $lead->status_id);
        $statusId      = Helper::getIdStatusByName($allStatuses['items'], $statusAmoName);

        if($contact->phone) $alfa_customer = $customer->findByPhone($contact->phone);
        if($contact->email) $alfa_customer = $customer->findByEmail($contact->email);

        if($alfa_customer) return $alfa_customer;
            else {
                $customer = $this->alfaApi->Customer;
                $customer->name = $contact->name;
                $customer->legal_type = 1;
                $customer->is_study = 0;
                $customer->phone = $contact->phone;
                $customer->legal_name = 'legal_name';
                $customer->study_status_id = 1;
                $customer->lead_status_id = $statusId;
                $customer->branch_ids = [1];
                //$customer->branch = 1;
                $customer->email = $contact->email;

                //$alfa_customer = $customer->create();

                dd($alfa_customer);
                return $alfa_customer;
            }
        //$arrayQueryContact['phone'] = self::clearPhone($contact->cf('Телефон')->getValue());
//        $arrayQueryContact['contact_id'] = $contact->id;
//        $arrayQueryContact['parent_name'] = $contact->name;
//        $arrayQueryContact['email'] = $contact->cf('Email')->getValue();
//        $arrayQueryContact['contact_age'] = $lead->cf('Дата рождения')->format('Y-m-d');
//        $arrayQueryContact['lead_id'] = $this->lead_id;
//        $arrayQueryContact['links'] = $contact->cf('Instagram')->getValue();
//        $arrayQueryContact['status_id'] = $lead->status_id;
//        $arrayQueryContact['branch'] = $lead->cf('Филиал')->getValue();
//        $arrayQueryContact['nps'] = $lead->cf('NPS')->getValue();
//
//        $leadSource   = $this->alfaCRM->LeadSource->getAll();
//        $arrayBranch  = $this->alfaCRM->Branch->getAllActive();
//        $leadSourceId = $this->DB->getLeadSourceId($leadSource, $leadDB['source']);
//        $leadBranchId = $this->DB->getBranchId($arrayBranch, $arrayQueryContact['branch']);

        //$result = $this->alfaCRM->Customer->update($leadDB['customer_id']);
//$this->alfaCRM->Customer->id = $leadDB['customer_id'];
//        $this->alfaCRM->Customer->name = $leadDB['fio'];
//        $this->alfaCRM->Customer->lead_source_id = $leadSourceId
        //     $this->alfaCRM->Customer->setBranchId($leadBranchId);
        //$result = $this->alfaCRM->Customer->update($leadDB['customer_id']);
    }

    public function createAlfa(Lead $lead, Contact $contact)
    {

    }

    public function updateAlfa(Lead $lead, Contact $contact)
    {

    }
}

