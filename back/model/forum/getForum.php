<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case "getForumName":
        getForumName($conn);
        break;
    case "getForumNameById": 
        $id = $_GET['id'];
        getForumNameById($conn, $id);
        break;
}



//获取所有板块名
function getForumName($conn){
    $sql = 'select id, forum_name from forums';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $forum_name = array();
    while($row = $stmt->fetch()){
        $forum_name[$row[0]] = $row[1];
    }
    echo json_encode($forum_name, JSON_UNESCAPED_UNICODE);
}

//根据id获取板块名
function getForumNameById($conn, $id){
    $sql = 'select id, forum_name from forums where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch();
    $resp = array();
    array_push($resp, $data[0], $data[1]);
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}