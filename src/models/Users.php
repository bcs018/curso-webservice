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

    public function create($name, $email, $pass){
        if(!$this->emailExists($email)){
            $sql = "INSERT INTO users (name, email, pass) VALUES (:name, :email, :pass)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":name", $name);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":pass", md5($pass));
            $sql->execute();

            $this->id_user = $this->db->lastInsertId();
            
            return true;
        }else{
            return false;
        }
    }

    private function emailExists($email){
        $sql = "SELECT * FROM users WHERE email = :email";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        
        return false;
    }
}