<?php
namespace core;

use \src\Config;

class Controller {

    // Metodos para padronizar o WebService

    // Pega o metodo utilizado de requisição
    public function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    // Pega os dados do metodo no mesmo padrão
    public function getRequestDada(){
        switch ($this->getMethod()) {
            case 'GET':
                return $_GET;
                break;

            case 'PUT':
            case 'DELETE':
                // Os dados vindo do PUT e DELETE vem em formato de query string de url
                $input = file_get_contents('php://input');
                // Transforma os dados em objetos de array
                parse_str($input, $data);
                // Retornando o objeto convertendo em array
                return (array) $data;

                break;

            case 'POST':
                // Os dados de post vem em formato de json, então pegar os dados e dar o json decode
                $data = json_decode(file_get_contents('php://input'));

                // Verificando se a requisiçao veio de um formulario com o global POST
                if(is_null($data)){
                    $data = $_POST;
                }

                return (array) $data;

                break;
            default:
                # code...
                break;
        }
    }

    // Responsavel pelo retorno em json
    public function returnJson($array){
        header("Content-Type: application/json");

        echo json_encode($array);
        exit;
    }

    // protected function redirect($url) {
    //     header("Location: ".$this->getBaseUrl().$url);
    //     exit;
    // }

    // private function getBaseUrl() {
    //     $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
    //     $base .= $_SERVER['SERVER_NAME'];
    //     if($_SERVER['SERVER_PORT'] != '80') {
    //         $base .= ':'.$_SERVER['SERVER_PORT'];
    //     }
    //     $base .= Config::BASE_DIR;
        
    //     return $base;
    // }

    // private function _render($folder, $viewName, $viewData = []) {
    //     if(file_exists('../src/views/'.$folder.'/'.$viewName.'.php')) {
    //         extract($viewData);
    //         $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
    //         $base = $this->getBaseUrl();
    //         require '../src/views/'.$folder.'/'.$viewName.'.php';
    //     }
    // }

    // private function renderPartial($viewName, $viewData = []) {
    //     $this->_render('partials', $viewName, $viewData);
    // }

    // public function render($viewName, $viewData = []) {
    //     $this->_render('pages', $viewName, $viewData);
    // }

}