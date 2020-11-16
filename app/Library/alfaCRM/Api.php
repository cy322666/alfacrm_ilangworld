<?php


namespace cy322666\AlfaCRM;

use cy322666\AlfaCRM\Base\Curl;

class Api
{
    private $prefixModel = 'cy322666\AlfaCRM\Models\\';
    public $Curl;
    public $Branch;
    public $Customer;
    public $LeadSource;
    public $Calendar;

    private $url = '/auth/login';
    private $authorization = false;
    private $token = '';

    private $access = [
        'email' => '',
        'api_key' => ''
    ];

    private $models = [
        'Branch' => '',
        'Customer' => '',
        'LeadSource' => '',
        'Calendar' => ''
    ];

    public function __construct($access)
    {
        require __DIR__.'/Base/Curl.php';
        //require_once __DIR__.'/Models/Branch.php';

        $this->initModels();
        $this->initAccess($access);
        $this->Curl = new Curl();
        $this->auth();
    }

    private function initModels()
    {
        foreach ($this->models as $key => $model) {
            require_once __DIR__.'/Models/'.$key.'.php';

            $className = __NAMESPACE__.'\Models\\'.$key;
            $class = str_replace($this->prefixModel, "", $className);

            //var_dump($this->Curl);

            $this->$class = new $className();

            //echo '<pre>'; print_r($this->$class); echo '</pre>';
        }
    }

    private function initAccess($access)
    {
        if($access) {
            $this->access['email'] = $access['email'];
            $this->access['api_key'] = $access['api_key'];
        }
    }

    private function auth()
    {
        $Response = Curl::Query($this->url, [
            'email' => $this->access['email'],
            'api_key' => $this->access['api_key']
        ]);
        if($Response != false) {
            $this->token = $Response;
            $this->authorization = true;
            file_put_contents('token.txt', $this->token);
        }
    }


}