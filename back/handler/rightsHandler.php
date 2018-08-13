<?php
require '../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
//判断登录/注销操作
$symbol = isset($_GET['check']);

$user_id = $_GET['user_id'];

switch($symbol){
    case 'canReadThread':
        canReadThread($conn, $user_id);
        break;
    case 'canReplyThread':
        canReplyThread($conn, $user_id);
        break;
    case 'canPostThread':
        canPostThread($conn, $user_id);
        break;
    case 'canReadUsers':
        canReadUsers($conn, $user_id);
        break;
    case 'canAddUsers':
        canAddUsers($conn, $user_id);
        break;
    case 'canModifyUsers':
        canModifyUsers($conn, $user_id);
        break;
    case 'canDeleteUsers':
        canDeleteUsers($conn, $user_id);
        break;    
}

function canReadThread($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(1, $user_groups);
}

function canReplyThread($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(2, $user_groups);
}
function canPostThread($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(3, $user_groups);
}
function canReadUsers($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(4, $user_groups);
}
function canAddUsers($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(5, $user_groups);
}
function canModifyUsers($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(6, $user_groups);
}
function canDeleteUsers($conn, $user_id){
    $user_groups = getUserInfo($conn, $user_id);
    echo in_array(7, $user_groups);
}

function getUserInfo($conn, $user_id){
    $sql = 'select user_groups from users where id = :user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_groups = $stmt->fetch()[0];
    return json_decode($user_groups, true);
}