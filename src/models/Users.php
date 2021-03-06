<?php
namespace src\models;
use \core\Model;

use src\models\Jwt;
use src\models\Photos;

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

    public function validateJwt($token){
        $jwt = new Jwt;
        $info = $jwt->validate($token);

        if(isset($info->id_user)){
            $this->id_user = $info->id_user;

            return true;
        }

        return false;
    }


    public function getId(){
        return $this->id_user;
    }

    public function create($name, $email, $pass){
        if(!$this->emailExists($email)){
            $sql = "INSERT INTO users (name, email, pass) VALUES (:name, :email, :pass)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":name" , $name      );
            $sql->bindValue(":email", $email     );
            $sql->bindValue(":pass" , md5($pass) );
            $sql->execute();

            $this->id_user = $this->db->lastInsertId();
            
            return true;
        }else{
            return false;
        }
    }

    public function getInfo($id){
        $array = [];

        $sql = 'SELECT * FROM users WHERE id = ?';
        $sql = $this->db->prepare($sql);
        $sql->bindValue(1, $id['id']);
        $sql->execute();

        if($sql->rowCount() > 0){
            // o PDO::FETCH_ASSOC faz a associação basica do atributos vindo do banco
            // exemplo: ele não retornara um array com as posições 0,1,2... será somente
            // o no nome dos campos que estão no sql
            $array = $sql->fetch(\PDO::FETCH_ASSOC);

            $photos = new Photos;

            if(!empty($array['avatar'])){
                $array['avatar'] = BASE_URL.'media/avatar/'.$array['avatar'];
            }else{
                $array['avatar'] = BASE_URL.'media/avatar/default.jpg';
            }

            $array['following'] = $this->getFollowingCount($id['id']);
            $array['followers'] = $this->getFollowersCount($id['id']);
            $aaray['photos_count'] = $photos->getPhotosCount($id['id']);
        }

        return $array;
    }

    public function getFollowingCount($id){
        $sql = "SELECT COUNT(*) AS C FROM users_following WHERE id_user_active = ?";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();

        $info = $sql->fetch();

        return $info['C'];
    }

    public function getFollowersCount($id){
        $sql = "SELECT COUNT(*) AS C FROM users_following WHERE id_user_passive = ?";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();

        $info = $sql->fetch();

        return $info['C'];
    }

    public function emailExists($email){
        $sql = "SELECT * FROM users WHERE email = :email";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        
        return false;
    }

    public function edit($id, $name='', $pass='', $email=''){
        $data = $this->getInfo($id);

        $sql = "UPDATE users SET name = :name, email = :email, pass = :pass WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':name' , ($name  == '') ? $data['name']  : $name      );
        $sql->bindValue(':email', ($email == '') ? $data['email'] : $email     );
        $sql->bindValue(':pass' , ($pass  == '') ? $data['pass']  : md5($name) );
        $sql->bindValue(':id'   ,  $id['id']                                   );
        
        if($sql->execute())
            return true;

        return false;
    }

    public function delete($id){
        $sql = "DELETE FROM users_following WHERE id_user_active = :id_act OR id_user_passive = :id_pass";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_act' , $id);
        $sql->bindValue(':id_pass', $id);
        $sql->execute();

        $sql = "DELETE FROM users WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

    public function getFeed($offset=0, $per_page=10){
        /**
         * 1 - Pegar os seguidores
         */
        $followingUsers = $this->getFollowing($this->getId);

        /**
         * 2 - Fazer uma lista das ultimas fts desses seguidores
         */
        $p = new Photos();

        return $p->getFeedCollection($followingUsers, $offset, $per_page);

    }

    public function getFollowing($id_user){
        $array = [];

        // Preencher os ids dos usuarios que eu sigo
        $sql = 'SELECT id_user_passive FROM users_following WHERE id_user_active = ?';
        $sql = $this->db->prepare($sql);
        $sql->bindValue(1, $this->id_user);
        $sql->execute();

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll();

            foreach($data as $item){
                $array[] = intval($item['id_user_passive']);
            }
        }

        return $array;
    }
}