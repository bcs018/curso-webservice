<?php
namespace src\controllers;

use \core\Controller;

class HomeController extends Controller {

    public function index() {
        print_r($this->getRequestDada());

        //echo "Metodo: ". $this->getMethod();

        $array = [ 
            'retornei' => 'Isso Fera',
            'aceita'   => 'AIIIII'
        ];

        $this->returnJson($array);
    }

}