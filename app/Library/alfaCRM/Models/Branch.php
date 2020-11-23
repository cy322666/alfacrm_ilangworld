<?php


namespace cy322666\AlfaCRM\Models;

use cy322666\AlfaCRM\Base\Curl as Curl;

class Branch
{
    private $Curl;
    private $url = '/branch/';

    public function __construct()
    {
        $this->Curl = new Curl();
    }

    public function getAllActive()
    {
        $link = $this->url . 'index';

        echo $link;
        $Response = $this->Curl::Query($link, [
            'is_active' => 1,
            'page' => 0
        ]);
       // echo '<pre>'; var_dump($Response['items']); echo '</pre>';
        return $Response['items'];

    }
}