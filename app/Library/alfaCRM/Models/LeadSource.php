<?php


namespace cy322666\AlfaCRM\Models;

use cy322666\AlfaCRM\Base\Curl as Curl;

class LeadSource
{
    private $Curl;
    private $url = '/1/lead-source/';

    public function __construct()
    {
        $this->Curl = new Curl();
    }

    public function getAll()
    {
        $link = $this->url . 'index';

        echo $link;
        $Response = $this->Curl::Query($link, [
//            'is_active' => 1,
            'page' => 0
        ]);
        return $Response;
        //echo '<pre>'; print_r($Response); echo '</pre>';
    }
}