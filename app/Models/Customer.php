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
        'customer_id',
    ];

    protected $primaryKey = 'customer_id';
    public $alfaApi;

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/app/Library/alfaCRM/Api.php';
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/resources/access/alfacrm.php';

        $this->alfaApi = new Api($access);
    }

    public function searchAlfa(Contact $contact)
    {
        $customer = $this->alfaApi->Customer;

        $customer->is_study = 0;
        $customer->branch = 1;

        if($contact->phone) $alfa_customer = $customer->findByPhone($contact->phone);
        if($contact->email && !$alfa_customer) $alfa_customer = $customer->findByEmail($contact->email);

        if($alfa_customer) return $alfa_customer;
            else return false;
        //$this->alfaCRM->Customer->setBranchId($leadBranchId);
    }

    public function createAlfa(Lead $lead, Contact $contact)
    {
        $allStatuses = $this->alfaApi->LeadStatus->getAll();

        $statusAmoName = Helper::getNameStatus($contact, $lead->status_id);
        $statusId      = Helper::getIdStatusByName($allStatuses['items'], $statusAmoName);

        $customer = $this->alfaApi->Customer;
        $customer->name = $contact->name;
        $customer->legal_type = 1;
        $customer->is_study = 0;
        $customer->phone = $contact->phone;
        $customer->legal_name = 'legal_name';
        $customer->study_status_id = 1;
        $customer->lead_status_id = $statusId;
        $customer->branch_ids = [1];
        $customer->branch = 1;
        $customer->email = $contact->email;

        dd($customer);
        $alfa_customer = $customer->create();

        return $alfa_customer['model'];
    }

    public function updateAlfa(Lead $lead, Contact $contact)
    {
        $allStatuses = $this->alfaApi->LeadStatus->getAll();

        $statusAmoName = Helper::getNameStatus($contact, $lead->status_id);
        $statusId      = Helper::getIdStatusByName($allStatuses['items'], $statusAmoName);

        $customer = $this->alfaApi->Customer;
        $customer->name = $contact->name;
        $customer->legal_type = 1;
        $customer->is_study = 0;
        $customer->phone = $contact->phone;
        $customer->legal_name = 'legal_name';
        $customer->study_status_id = 1;
        $customer->lead_status_id = $statusId;
        $customer->branch_ids = [1];
        $customer->branch = 1;
        $customer->email = $contact->email;

        dd($customer);
        $alfa_customer = $customer->update();

        return $alfa_customer['model'];
    }
}

