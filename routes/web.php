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


//$router->get('/', 'CustomerController@create');
$router->get('/', 'LeadController@create');

//$router->post('/leads/create', 'LeadController@create');
//$router->post('/leads/update', 'LeadController@update');

//$router->post('/customer/create', 'CustomerController@create');
//$router->post('/customer/update', 'CustomerController@update');
//$router->post('/tariff/create', 'TariffController@create');
//$router->post('/tariff/update', 'TariffController@update');
