<?php


namespace cy322666\AlfaCRM\Models;

use cy322666\AlfaCRM\Base\Curl as Curl;

class Customer
{
    public $id;
    public $dob;
    public $name;
    public $note;
    public $is_study;
    public $legal_type;
    public $phone;
    public $legal_name;
    public $study_status_id;
    public $teacher;
    public $branch_ids;
    public $branch;
    public $datetime_trial;
    //public $lead_source_id;
    public $lead_status_id;
    public $method;
    public $languange;
    public $loyalty;
    public $email;

    private $Curl;
    private $url = '/customer/';

    public function __construct()
    {
        $this->Curl = new Curl();
    }

    private function buildArrayFelds()
    {
        $arrayCreateFields = [
            'name' => $this->name,
            'branch_ids' => $this->branch_ids,
            'is_study' => $this->is_study,
            'lead_status_id' => $this->lead_status_id,
            //'lead_source_id' => $this->lead_source_id,
            'legal_type' => $this->legal_type,
            'legal_name' => $this->legal_name,
            'study_status_id' => $this->study_status_id,
            'custom_datetime_trial' => $this->datetime_trial,
            'custom_teacher' => $this->teacher,
            'custom_method' => $this->method,
            'custom_languange' => $this->languange,
            'custom_loyalty' => $this->loyalty,
            'phone' => $this->phone,
            'email' => $this->email,
            'dob' => $this->dob,
            //'note' => $this->note
        ];

        return $arrayCreateFields;
    }

    public function findByPhone($phone)
    {
        $link = '/'.$this->branch.'/customer/index';

        $Response = $this->Curl::Query($link, [
            'phone' => $phone,
            'is_study' => $this->is_study,
            'branch_ids' => [$this->branch]
        ]);

        if(!empty($Response['items'][0])) return $Response;
        else return false;
    }

    public function findByEmail($email)
    {
        $link = '/'.$this->branch.'/customer/index';

        $Response = $this->Curl::Query($link, [
            'email' => $email,
            'is_study' => $this->is_study,
            'branch_ids' => [$this->branch]
        ]);

        if(!empty($Response['items'][0])) return $Response;
        else return false;
    }

    private function parseResponse($Response)
    {
//        $this->id = $Response['id'];
//        $this->branch_ids = $Response['branch_ids'];
//        $this->is_study = $Response['is_study'];
//        $this->legal_type = $Response['legal_type'];
//        $this->lead_status_id = $Response['lead_status_id'];
//        $this->phone = $Response['phone'];
//        $this->email = $Response['email'];
//        $this->note = $Response['note'];
//        $this->dob = $Response['dob'];
    }

    public function update($id)
    {
        $link = '/'.$this->branch.'/customer/update?id='.$id;

        $arrayQuery = $this->buildArrayFelds();
        $Response = $this->Curl::Query($link, $arrayQuery);

        return $Response;
    }

    public function findById($id, $study)
    {
        $this->branch = 1;
        $this->is_study = $study;

        $link = '/'.$this->branch.'/customer/index';

        $Response = $this->Curl::Query($link, [
            'id' => $id,
            'is_study' => $this->is_study,
            'branch_ids' => [$this->branch]
        ]);

        if(!empty($Response['items'][0])) return $Response;
        else return false;
    }

    public function setBranchId($id)
    {
        $this->branch = $id;
    }

    public function create()
    {
        $link = $this->url . 'create';

        $arrayCreateFields = $this->buildArrayFelds();
        $Response = $this->Curl::Query($link, $arrayCreateFields);

        return $Response;
    }
}