<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->delete('/', 'HomeController@index');
$router->post('/', 'HomeController@index');
$router->put('/', 'HomeController@index');
$router->get('/sobre', 'HomeController@sobre');