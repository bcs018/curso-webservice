<?php
namespace src\controllers;

use \core\Controller;

class ErrorController extends Controller {

    public function index() {
        $array = ['error'=>'Método de requisição inválido'];

        $this->returnJson($array);
    }

}