<?php
namespace core;

use \core\Database;

class Model {

    //protected static $_h;
    protected $db;
    
    public function __construct() {
        $this->checkH();
    }

    public function checkH() {
        if($this->db == null) {
            $data = new Database();
            $this->db = $data->getInstance();
        }
        
    }
} 