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
            $this->conn->query("set names utf8");
            return $this->conn;
        } catch (PDOException $e) {
            exit("connected error:".$e->getMessage()); 
        }
    }
}
// $a = new Database();
// $conn = $a->connect();
// $stmt=$conn->prepare("select * from threads");
// $stmt->execute();
// while($row = $stmt->fetch()) {
//     print_r($row);
// }
