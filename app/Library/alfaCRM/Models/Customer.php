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
    public $teacher_ids;
    public $branch_ids;
    public $custom_id_amo_sdelka;
    public $lead_source_id;
    public $lead_status_id;
    public $method;
    public $target;
    public $changes;
    public $nps;
    public $klass;
    public $branch;
    public $links;
    public $level;
    public $email;
    public $school;
    public $product;
    public $date_trial;
    public $employment;

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
            'lead_source_id' => $this->lead_source_id,
            'legal_type' => $this->legal_type,
            'legal_name' => $this->legal_name,
            'study_status_id' => $this->study_status_id,
//            'custom_date_trial' => $this->date_trial,
//            'custom_id_amo_sdelka' => $this->custom_id_amo_sdelka,
//            'custom_level' => $this->level,
//            'custom_method' => $this->method,
//            'custom_target' => $this->target,
//            'custom_changes' => $this->changes,
//            'custom_nps' => $this->nps,
//            'custom_employment' => $this->employment,
//            'custom_links' => $this->links,
//            'custom_school' => $this->school,
//            'custom_product' => $this->product,
            'phone' => $this->phone,
            'email' => $this->email,
            'custom_klass' => $this->klass,
            'dob' => $this->dob,
            'note' => $this->note
        ];

        return $arrayCreateFields;
    }

    public function findByPhone($phone)
    {
        $link = '/'.$this->branch.'/customer/index';

        echo $link;
        $Response = $this->Curl::Query($link, [
            'phone' => $phone,
            'is_study' => $this->is_study,
            'branch_ids' => [$this->branch]
        ]);

        $this->parseResponse($Response);
        return $Response;
    }

    public function getStudyForPipeline($pipeline_id)
    {
        if($pipeline_id == '2247049') {
            return 0;
        }
        if($pipeline_id == '3365215') {
            return 1;
        }
    }

    public function getConvertStatus($status_id)
    {
        $arrayStatuses = require PATH . 'app/config/statuses.php';
        foreach ($arrayStatuses as $status) {
            if($status_id == $status['alfaCRM']) {
                return $status['amoCRM'];
            }
            if($status_id == $status['amoCRM']) {
                return $status['alfaCRM'];
            }
        }
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

    public function findById($id)
    {
        $link = '/'.$this->branch.'/customer/index';

        $Response = $this->Curl::Query($link, [
            'id' => $id,
            'is_study' => $this->is_study,
            'branch_ids' => [1]
        ]);

        return $Response;
    }

    public function setBranchId($id)
    {
        $this->branch = $id;
    }

    public function create()
    {
        $link = $this->url . 'create';
        $arrayCreateFields = $this->buildArrayFelds();
        //echo $link;

        $Response = $this->Curl::Query($link, $arrayCreateFields);
        //echo '<pre>'; print_r($Response); echo '</pre>';

        return $Response;
//        if($Response['success'] != false) {
//            $this->id = $Response['model']['id'];
//            return true;
//            //$this->parseResponse($Response);
//        }
    }
}