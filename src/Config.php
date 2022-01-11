<?php
namespace src;

define('JWT_SECRET_KEY', 'abC123!');
define('BASE_URL', 'http://localhost/cursoPhp/webservice/api_devstragram/curso-webservice/');

class Config {
    const BASE_DIR = '/mvc/public';

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'devstagram';
    CONST DB_USER = 'root';
    const DB_PASS = '';

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';
}