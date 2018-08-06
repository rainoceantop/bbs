<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'getTagGroups':
        $forum_id = $_GET['id'];
        getTagGroups($conn, $forum_id);
        break;
    case 'getTags':
        $tag_group_id = $_GET['tag_group_id'];
        $forum_id = $_GET['forum_id'];
        getTags($conn, $tag_group_id, $forum_id);
        break;
}


//获取标签组
function getTagGroups($conn, $forum_id){
    $sql = 'select id, tag_group_name from tag_groups where forum_id = :forum_id';
    $stmt = $conn -> prepare($sql);
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->execute();
    $data = array();
    $resp = array();
    while($row = $stmt->fetch()){
        $data['tag_group_id'] =  $row['id'];
        $data['tag_group_name'] = $row['tag_group_name'];
        array_push($resp, $data);
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}



//根据标签组id获取标签
function getTags($conn, $tag_group_id, $forum_id){
    $sql = 'select id, tag_name from tags where tag_group_id = :tag_group_id';
    $stmt = $conn -> prepare($sql);
    $stmt->bindParam(':tag_group_id', $tag_group_id);
    $stmt->execute();
    $data = array();
    $resp = array();
    while($row = $stmt->fetch()){
        $data['tag_id'] =  $row['id'];
        $data['tag_name'] = $row['tag_name'];
        array_push($resp, $data);
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}