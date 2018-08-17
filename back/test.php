<?php
require 'database/database.php';

$pdo = new Database();
$conn = $pdo->connect();


$sql = 'select collections from users where id = :id';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', 20);
$stmt->execute();
$user_c = json_decode($stmt->fetch()[0], true);
foreach($user_c as $c){
    echo $c;
}
var_dump($user_c);