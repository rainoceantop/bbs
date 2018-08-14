<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'delTagGroupById':
    $id = $_GET['id'];
    delTagGroupById($conn, $id);
        break;
        case 'delTagById':
    $id = $_GET['id'];
    delTagById($conn, $id);
        break;
}

function delTagGroupById($conn, $id){
    $sql = 'delete from tag_groups where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        echo 'SUCCESS';
    } catch(PDOException $e){
        echo 'ERROR：'.$e->getMessage();
    }
}
function delTagById($conn, $id){
    $sql = 'delete from tags where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        echo 'SUCCESS';
    } catch(PDOException $e){
        echo 'ERROR：'.$e->getMessage();
    }
}
