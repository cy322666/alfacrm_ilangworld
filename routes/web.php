<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//middleware для проверки как давно было обновление сущности которая пришла?


//$router->get('/', 'CustomerController@update_status');
//$router->get('/', 'LeadController@create');

$router->get('/leads/create', 'LeadController@create');
$router->get('/tariff/create', 'TariffController@create');
$router->get('/tariff/pay', 'TariffController@pay');
//$router->post('/leads/update', 'LeadController@update');

//$router->post('/customer/create', 'CustomerController@create');
$router->get('/customer/update_status', 'CustomerController@update_status');
//$router->post('/tariff/create', 'TariffController@create');
//$router->post('/tariff/update', 'TariffController@update');
