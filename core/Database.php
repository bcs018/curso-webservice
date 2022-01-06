<?php
namespace core;

use \src\Config;

class Database {
    private $_pdo;
    public function getInstance() {
        if(!isset($this->_pdo)) {
            $this->_pdo = new \PDO(Config::DB_DRIVER.":dbname=".Config::DB_DATABASE.";host=".Config::DB_HOST, Config::DB_USER, Config::DB_PASS);
            //$this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $this->_pdo;
    }

    //private function __construct() { }
    //private function __clone() { }
    //private function __wakeup() { }
}