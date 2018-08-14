<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case "delForumById":
    $id = $_GET['id'];
    delForumById($conn, $id);
        break;
}


function delForumById($conn, $id){
    $sql = 'delete from forums where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        echo 'SUCCESS';
    } catch(PDOException $e){
        echo 'ERROR：'.$e->getMessage();
    }
}