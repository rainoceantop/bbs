<?php

class Database {
    private $db_config;
    private $conn;
    public function __construct() {
        $this->db_config = require dirname(__FILE__, 2)."/config/database.php";
    }
    public function connect() {
        extract($this->db_config);
        try {
            $this->conn = new PDO($db.":host=".$host.";dbname=".$dbname.";port=".$port, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $this->conn->query("set names utf8");
            return $this->conn;
        } catch (PDOException $e) {
            exit("connected error:".$e->getMessage()); 
        }
    }
}
