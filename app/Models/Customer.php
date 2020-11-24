<?php

namespace App\Models;

use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use cy322666\AlfaCRM\Api;

class Customer extends Model
{
    protected $attributes = array(
        'datetime_trial' => '',
        'teacher' => '',
        'method' => '',
        'languange' => '',
        'email' => '',
        'phone' => '',
        'loyalty' => '',
    );

    protected $primaryKey = 'customer_id';

    private $study;

    public $alfaApi;

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/app/Library/alfaCRM/Api.php';
        $access = require $_SERVER['DOCUMENT_ROOT'].'/alfacrm_ilangworld/resources/access/alfacrm.php';

        $this->alfaApi = new Api($access);
    }

    public function searchAlfa(Contact $contact, $study)
    {
        $customer = $this->alfaApi->Customer;

        $customer->is_study = $study;
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
        $customer->legal_name = $contact->name;
        $customer->study_status_id = 1;
        $customer->lead_status_id = $statusId;
        $customer->branch_ids = [1];
        $customer->branch = 1;
        $customer->email = $contact->email;
        //$customer->sex        = $contact->sex;    //Пол (контакт)
        //$customer->age        = $contact->age;    //Возраст (контакт)
        $customer->datetime_trial = $lead->datetime_trial;
        $customer->teacher    = $lead->teacher;
        $customer->method     = $lead->method;
        $customer->languange  = $lead->languange;
        $customer->loyalty  = $contact->loyalty;



        $alfa_customer = $customer->create();

        return $alfa_customer['model'];
    }

    public function setStudy($study)
    {
        $this->study = $study;
    }

    public function updateAlfa($id, Lead $lead, Contact $contact)
    {
        if($lead->customer_id) {
            $allStatuses = $this->alfaApi->LeadStatus->getAll();

            $statusAmoName = Helper::getNameStatus($contact, $lead->status_id);
            $statusId      = Helper::getIdStatusByName($allStatuses['items'], $statusAmoName);

            $customer = $this->alfaApi->Customer;
            $customer->name = $contact->name;
            $customer->legal_type = 1;
            $customer->is_study = $this->study;
            $customer->phone = $contact->phone;
            $customer->legal_name = $contact->name;
            $customer->loyalty = $contact->loyalty;
            $customer->study_status_id = 1;
            $customer->lead_status_id = $statusId;
            $customer->branch_ids = [1];
            $customer->branch = 1;
            $customer->count_lessons = $lead->count_lessons;
            $customer->email = $contact->email;
            $customer->datetime_trial = $lead->datetime_trial;
            $customer->teacher = $lead->teacher;
            $customer->method = $lead->method;
            $customer->languange = $lead->languange;
            $customer->date_start = $lead->date_start;
            $customer->date_finish = $lead->date_finish;
            $customer->count_mouth = $lead->count_mouth;
            $customer->rate = $lead->rate;
            $customer->os_learner = $lead->os_learner;
            $customer->os_teacher = $lead->os_teacher;

            $alfa_customer = $customer->update($id);

            return $alfa_customer['model'];
        } else {
            return false;
        }
    }
}

