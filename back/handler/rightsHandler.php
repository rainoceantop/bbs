<?php
require '../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
//判断登录/注销操作
$symbol = $_GET['check'];

$user_id = $_GET['user_id'];
$user_rights = getUserInfo($conn, $user_id);

switch($symbol){
    case 'canReadThread':
        canReadThread($conn, $user_id, $user_rights);
        break;
    case 'canReplyThread':
        canReplyThread($conn, $user_id, $user_rights);
        break;
    case 'canPostThread':
        canPostThread($conn, $user_id, $user_rights);
        break;
    case 'canEditThread':
        canEditThread($conn, $user_id, $user_rights);
        break;
    case 'canReadUsers':
        canReadUsers($conn, $user_id, $user_rights);
        break;
    case 'canAddUsers':
        canAddUsers($conn, $user_id, $user_rights);
        break;
    case 'canModifyUsers':
        canModifyUsers($conn, $user_id, $user_rights);
        break;
    case 'canDeleteUsers':
        canDeleteUsers($conn, $user_id, $user_rights);
        break;    
    case 'canModifyFT':
        canDeleteUsers($conn, $user_id, $user_rights);
        break;    
}


function canReadThread($conn, $user_id, $user_rights){
    echo in_array(1, $user_rights);
}

function canReplyThread($conn, $user_id, $user_rights){
    echo in_array(2, $user_rights);
}
function canPostThread($conn, $user_id, $user_rights){
    echo in_array(3, $user_rights);
}
function canEditThread($conn, $user_id, $user_rights){
    echo in_array(4, $user_rights);
}
function canReadUsers($conn, $user_id, $user_rights){
    echo in_array(5, $user_rights);
}
function canAddUsers($conn, $user_id, $user_rights){
    echo in_array(6, $user_rights);
}
function canModifyUsers($conn, $user_id, $user_rights){
    echo in_array(7, $user_rights);
}
function canDeleteUsers($conn, $user_id, $user_rights){
    echo in_array(8, $user_rights);
}
function canModifyFT($conn, $user_id, $user_rights){
    echo in_array(9, $user_rights);
}

//查找用户组
function getUserInfo($conn, $user_id){
    $sql = 'select user_groups from users where id = :user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_groups = $stmt->fetch()[0];
    return getUserRights($conn, json_decode($user_groups, true));
}

//查找用户组权限。返回并集
function getUserRights($conn, $group_arr){
    $sql = 'select rights from user_groups where id = :user_group_id';
    $rights = array();
    $conn->beginTransaction();
    foreach($group_arr as $group_id){
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_group_id', $group_id);
        $stmt->execute();
        $right = $stmt->fetch();
        if(!empty($right)){
            //并集用户所属用户组权限（先合并再去重）
            $rights = array_merge($rights, json_decode($right[0], true));
        }

    }
    return array_unique($rights);
}