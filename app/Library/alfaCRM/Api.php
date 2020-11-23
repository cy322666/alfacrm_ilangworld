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
    public $LeadStatus;
    public $Calendar;
    public $Tariff;
    public $Lesson;

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
        'LeadStatus' => '',
        'Calendar' => '',
        'Tariff' => '',
        'Lesson' => '',
    ];

    public function __construct($access)
    {
        require_once __DIR__.'/Base/Curl.php';

        $this->initModels();
        $this->initAccess($access);
        $this->auth();
    }

    private function initModels()
    {
        foreach ($this->models as $key => $model) {
            require_once __DIR__.'/Models/'.$key.'.php';

            $className = __NAMESPACE__.'\Models\\'.$key;
            $class = str_replace($this->prefixModel, "", $className);
            $this->$class = new $className();
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
        $this->Curl = new Curl();

        $Response = Curl::Query($this->url, [
            'email' => $this->access['email'],
            'api_key' => $this->access['api_key']
        ]);
        if($Response != false) {
            $this->token = $Response;
            $this->authorization = true;
            file_put_contents(storage_path('token.txt'), $this->token);
        }
    }


}