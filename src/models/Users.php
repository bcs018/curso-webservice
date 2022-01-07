<?php
namespace src\models;
use \core\Model;

use src\models\Jwt;

class Users extends Model {
    // Id do usuario para poder usar no futuro
    private $id_user;

    public function checkCredentials($email, $pass){
        $sql = "SELECT * FROM users WHERE email = :email AND pass = :pass";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':email', $email);
        $sql->bindValue(':pass', md5($pass));
        $sql->execute();

        if($sql->rowCount() > 0){
            $info = $sql->fetch();
           
            $this->id_user = $info['id'];
                
            return true;
        }else{
            return false;
        }
    }

    public function createJwt(){
        $jwt = new Jwt;
        return $jwt->create(array('id_user'=>$this->id_user));
    }
}