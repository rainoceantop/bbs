<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'tagGroups':
    $data = file_get_contents('php://input');
    $data = json_decode($data, TRUE);
    addTagGroup($conn, $data);
        break;
    case 'tags':
    $data = file_get_contents('php://input');
    $data = json_decode($data, TRUE);
    addTag($conn, $data);
        break;
}

function addTagGroup($conn, $data){
    $tag_group = array();
    $tag_group['forum_id'] = $data['forum_id'];
    $tag_group['tag_group_name'] = $data['tag_group_name'];
    
    $sql = 'insert into tag_groups value(NULL, :forum_id, :name)';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':forum_id', $tag_group['forum_id']);
    $stmt->bindParam(':name', $tag_group['tag_group_name']);
    try{
        $stmt->execute();
        echo 'SUCCESS';
    } catch(PDOException $e){
        echo 'ERROR:'.$e->getMessage();
    }
}


function addTag($conn, $data){
    $tag = array();
    $tag['forum_id'] = $data['forum_id'];
    $tag['tag_group_id'] = $data['tag_group_id'];
    $tag['name'] = $data['tag_name'];
    
    $sql = 'insert into tags value(NULL, :forum_id, :tag_group_id, :name)';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':forum_id', $tag['forum_id']);
    $stmt->bindParam(':tag_group_id', $tag['tag_group_id']);
    $stmt->bindParam(':name', $tag['name']);
    try{
        $stmt->execute();
        echo 'SUCCESS';
    } catch(PDOException $e){
        echo 'ERROR:'.$e->getMessage();
    }
}

