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
}