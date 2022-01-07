<?php
namespace src\controllers;

use \core\Controller;
use src\models\Users;


class UserController extends Controller {

    public function index() {
       
    }

    public function login(){
        // Array de resposta
        $array = ['error'=>''];

        // Pega o metodo da requisição
        $method = $this->getMethod();
        // Dados que que o usu enviou
        $data = $this->getRequestDada();

        if($method == 'POST'){
            if(empty($data['email']) && empty($data['pass'])){
                $array['error'] = 'Email e/ou senha não preenchidos';
            }else{
                $users = new Users; 

                if($users->checkCredentials($data['email'], $data['pass'])){
                    // Gera o JWT
                    $array['jwt'] = $users->createJwt();
                }else{
                    $array['error'] = 'Acesso negado';
                }
            }
        }else{
            $array['error'] = 'Método de reuisição inconpativel';
        }

        $this->returnJson($array);
    }

}