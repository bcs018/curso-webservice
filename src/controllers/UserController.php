<?php
namespace src\controllers;

use \core\Controller;
use src\models\Photos;
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
        $data = $this->getRequestData();

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
        $data = $this->getRequestData();

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
        $array = ['error'=>'', 'logged'=>false];

        $data = $this->getRequestData();

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

            $array['data'] = $users->getInfo($id);

            if(count($array['data']) === 0){
                $array['error'] = "Usuario não existe";
            }

        }else{
            $array['error'] = 'Acesso negado';
        }

        $this->returnJson(($array));
    }

    public function edit($id){
        $array = ['error'=>'', 'logged'=>false];

        $data = $this->getRequestData();
        $users = new Users;

        if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])){
            $array['logged'] = true;
            $tochange = [];
            $tochange['name']  = '';
            $tochange['email'] = '';
            $tochange['pass']  = '';

            if($id['id'] == $users->getId()){
                if(isset($data['name'])){
                    if(!empty($data['name'])){
                        $tochange['name'] = $data['name'];
                    }
                }
                if(isset($data['email'])){
                    if(!empty($data['email'] && filter_var($data['email'], FILTER_VALIDATE_EMAIL) && !$users->emailExists($data['email']))){
                        $tochange['email'] = $data['email'];
                    }else{
                        $array['error'] = 'E-mail inválido ou já existente';
                        $this->returnJson($array);
                        exit;
                    }
                }
                if(isset($data['pass'])){
                    if(!empty($data['pass'])){
                        $tochange['pass'] = $data['pass'];
                    }
                }

                if(count($tochange) > 0){
                    //fazer o update
                    if($users->edit($id, $tochange['name'], $tochange['pass'], $tochange['email']))
                        $array['error'] = 'Usuário alterado com sucesso!';
                    else
                        $array['error'] = 'Erro interno ao alterar dados';
                }
            }else{
                $array['error'] = 'Sem permissão para editar esse usuário';
            }
        }else{
            $array['error'] = 'Area restrita, faça login para continuar';
        }
        $this->returnJson($array);
    }

    public function del($id){
        $array = ['error'=>'', 'logged'=>false];

        $data = $this->getRequestData();
        $users = new Users;

        if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])){
            if($users->getId() == $id['id']){

                $photos = new Photos;
                $photos->delete($id['id']);
    
                $users->delete($id['id']);
               
                $array['error'] = 'Usuário deletado com sucesso';
    
            }else{
                $array['error'] = 'Sem permissão para deletar esse usuário';
            }
        }else{
            $array['error'] = 'Area restrita, faça login para continuar';
        }
        

        $this->returnJson($array);
    }

    public function feed(){
        $array = ['error'=>'', 'logged'=>false];

        $data = $this->getRequestData();

        $users = new Users;

        // Valida o jwt e verfica se não está preenchido
        if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])){
            $array['logged'] = true;

            // a partir de qual item começa a mostrar 
            $offset = 0;
            if(!empty($data['offset'])){
                $offset = intval($data['offset']);
            }

            //qtd itens por pagina
            $per_page = 10;
            if(!empty($data['per_page'])){
                $per_page = intval($data['per_page']);
            }

            $array['data'] = $users->getFeed($offset, $per_page);



        }else{
            $array['error'] = 'Acesso negado';
        }

        $this->returnJson(($array));

    }

}