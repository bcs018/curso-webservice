<?php
session_start();

//Libera todos os sites para acessos de requisição para minha aplicação (GET e POST)
header("Access-Control-Allow-Origin: *");

//Libera todos os tipos de requisições http (POST, GET, PUT, DELETE....)
header("Access-Control-Allow-Methods: *");

require '../vendor/autoload.php';
require '../src/routes.php';

$router->run( $router->routes );