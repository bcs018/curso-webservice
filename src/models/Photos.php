<?php
namespace src\models;
use \core\Model;

use src\models\Jwt;

class Photos extends Model {
    public function getPhotosCount($id){
        $sql = "SELECT COUNT(*) AS C FROM photos WHERE id_user = ?";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
    
        $info = $sql->fetch();
    
        return $info['C'];
    }

    public function delete($id){
        $sql = "DELETE FROM photos_comments WHERE id_user = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        $sql = "DELETE FROM photos_likes WHERE id_user = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        $sql = "DELETE FROM photos WHERE id_user = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

    public function getFeedCollection($ids, $offset, $per_page){
        $array = [];
        $users = new Users;

        if(count($ids) > 0){
            $sql = 'SELECT * FROM photos WHERE is_user IN ('.implode(',',$ids).') ORDER BY id DESC LIMIT ?, ?';
            $sql = $this->db->prepare($sql);
            $sql->bindValue(1,);
            $sql->bindValue(2, $offset);
            $sql->bindValue(3, $per_page);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll(\PDO::FETCH_ASSOC);

                foreach($array as $k => $item){
                    $user_info = $users->getInfo(($item['id_user']));
                    
                    $array[$k]['name'] = $user_info['name'];
                    $array[$k]['avatar'] = $user_info['avatar'];
                    
                    $array[$k] = BASE_URL.'media/photos/'.$item['url'];
                }
            }
        }

        return $array;
    }
}