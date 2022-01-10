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

    public function new_record(){
        $array = ['error'=>''];

        $method = $this->getMethod();
        $data = $this->getRequestDada();

        if($method == 'POST'){

            if(!empty($data['name']) && !empty($data['pass']) && !empty($data['email'])){
                if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                    $users = new Users;

                    if($users->create($data['name'], $data['email'], $data['pass'])){
                        $array['jwt'] = $users->createJwt();
                    }else{
                        $array['error'] = 'Email já existe na base de dados';
                    }
                }else{
                    $array['error'] = 'Email invalido';
                }
            }else{
                $array['error'] = 'Dados não preenchidos';
            }

        }else{
            $array['error'] = 'Método de reuisição inconpativel';
        }

        $this->returnJson(($array));
    }

}