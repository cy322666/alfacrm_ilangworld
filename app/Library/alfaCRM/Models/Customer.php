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
    public $lead_status_id;
    public $method;
    public $languange;
    public $loyalty;
    public $email;
    public $date_start;
    public $date_finish;
    public $count_mouth;
    public $count_lessons;
    public $rate;
    public $os_learner;
    public $os_teacher;

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
            'custom_count_lessons' => $this->count_lessons,
            'custom_loyalty' => $this->loyalty,
            'custom_date_start' => $this->date_start,
            'custom_date_finish' => $this->date_finish,
            'custom_count_mouth' => $this->count_mouth,
            'custom_rate' => $this->rate,
            'custom_os_learner' => $this->os_learner,
            'custom_os_teacher' => $this->os_teacher,
            'phone' => $this->phone,
            'email' => $this->email,
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

        if(!empty($Response['items'][0])) return $Response['items'][0];
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

        if(!empty($Response['items'][0])) return $Response['items'][0];
        else return false;
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

        if(!empty($Response['items'][0])) return $Response['items'][0];
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