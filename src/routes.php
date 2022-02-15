<?php
use core\Router;

$router = new Router();

$router->post('/users/login', 'UserController@login');
$router->post('/users/new', 'UserController@new_record');
$router->get('/users/{id}', 'UserController@view');
$router->put('/users/{id}', 'UserController@edit');
$router->delete('/users/{id}', 'UserController@del');
$router->get('/users/feed', 'UserController@feed');
$router->get('/users/{id}/photos', 'UserController@photos');
$router->post('/users/{id}/follow', 'UserController@follow');
$router->delete('/users/{id}/follow', 'UserController@unfollow');

$router->get('/photos/ramdom', 'PhotosController@ramdom');
$router->post('/photos/new', 'PhotosController@new_record');
$router->get('/photos/{id}', 'PhotosController@view');
$router->delete('/photos/{id}', 'PhotosController@del');
$router->post('/photos/{id}/comment', 'PhotosController@comment');
$router->delete('/photos/{id}/comment', 'PhotosController@uncomment');
$router->post('/photos/{id}/like', 'PhotosController@like');
$router->delete('/photos/{id}/like', 'PhotosController@unlike');


// $router->delete('/', 'HomeController@index');
// $router->post('/', 'HomeController@index');
// $router->put('/', 'HomeController@index');
// $router->get('/sobre', 'HomeController@sobre');