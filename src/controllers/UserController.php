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

    public function view($id){
        $array = ['error'=>"", 'logged'=>false];

        $method = $this->getMethod();
        $data = $this->getRequestDada();

        $users = new Users;

        // Valida o jwt e verfica se não está preenchido
        if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])){
            $array['logged'] = true;

            // Flag para ver se o usuário que esta querendo as informações é o que está logado
            $array['is_me'] = false;
            // Verifica se o id enviado bate com o id salvo do usu logado
            if($id['id'] == $users->getId()){
                $array['is_me'] = true;
            }

            switch($method){
                case 'GET':
                    $array['data'] = $users->getInfo($id);

                    if(count($array['data']) === 0){
                        $array['error'] = "Usuario não existe";
                    }
                    break;

                case 'PUT':
                    break;

                case 'DELETE':
                    break;

                default:
                    $array['error'] = 'Método '.$method.' não disponivel';
                    break;
            }
        }else{
            $array['error'] = 'Acesso negado';
        }

        $this->returnJson(($array));

    }

}