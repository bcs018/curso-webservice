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

    public function getFeedCollection($followingUsers, $offset, $per_page){

    }
}