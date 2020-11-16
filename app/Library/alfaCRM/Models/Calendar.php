<?php


namespace cy322666\AlfaCRM\Models;

use cy322666\AlfaCRM\Base\Curl as Curl;

class Calendar
{
    private $Curl;
    private $url = '/2/calendar/';
    public $b_date;
    public $e_date;

    public function __construct()
    {
        $this->Curl = new Curl();
    }

    public function get($customer_id)
    {
        $link = $this->url . 'customer?id='.$customer_id.'&date1='.$this->b_date.'&date2='.$this->e_date;

        //echo $link;
        $Response = $this->Curl::Query($link, [
//            'is_active' => 1,
            'page' => 0
        ]);
        return $Response;
        //echo '<pre>'; print_r($Response); echo '</pre>';
    }
}