<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'getUserById':
        $user_id = $_GET['id'];
        getUserById($conn, $user_id);
        break;
    case 'getUserGroups':
        getUserGroups($conn);
        break;
    case 'getGroupsUsers':
        getGroupsUsers($conn);
        break;
    case 'getUsersWithout':
        $group_id = $_GET['id'];
        getUsersWithout($conn, $group_id);
        break;
    case 'getUsersWithin':
        $group_id = $_GET['id'];
        getUsersWithin($conn, $group_id);
        break;
}

//根据id查询用户
function getUserById($conn, $user_id){
    $sql = 'select name, avatar, created_at, last_online from users where id = :user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $info = array();

    while($row = $stmt->fetch()){
        $info['user'] = $row['name'];
        $info['avatar'] = $row['avatar'];
        $info['created_at'] = $row['created_at'];
        $info['last_online'] = $row['last_online'];
    }
    echo json_encode($info, JSON_UNESCAPED_UNICODE);
}

//查询所有用户组
function getUserGroups($conn){
    $conn -> beginTransaction();
    $sql = 'select * from user_groups';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $resp = array();
    while($row = $stmt->fetch()){
        $info = array(
            'user_group_id' => $row['id'],
            'user_group_name' => $row['user_group_name']
        );
        $rights = json_decode($row['rights'], 1);
        $rnames = array();
        foreach($rights as $right){
            $sql = 'select right_name from rights where id = :right_id';
            $s = $conn->prepare($sql);
            $s->bindParam(':right_id', $right);
            $s->execute();
            array_push($rnames, $s->fetch()[0]);
        }
        $info['rights'] = $rnames;
        array_push($resp, $info);
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}

//查看已加入用户组的所有用户
function getGroupsUsers($conn){
    $sql = 'select * from users where user_groups != "0"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll());
}
//查看为加入指定用户组id的用户
function getUsersWithout($conn, $group_id){
    $conn->beginTransaction();
    try{
        $sql = 'select * from users';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resp = array();
        $info = array();
        while($row = $stmt->fetch()){
            $user_groups = json_decode($row['user_groups'], true);
            if($user_groups == 0){
                $info['user_id'] = $row['id'];
                $info['user_name'] = $row['name'];
                array_push($resp, $info);
            } else {
                if(!in_array($group_id, $user_groups)){
                    $info['user_id'] = $row['id'];
                    $info['user_name'] = $row['name'];
                    array_push($resp, $info);
                }
            }
        }
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }catch(PDOException $e){
        $conn->rollBack();
        echo '出错：'.$e->getMessage();
    }
}

//查看加入指定用户组id的用户
function getUsersWithin($conn, $group_id){
    try{
        $sql = 'select * from users where user_groups like "%":group_id"%"';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->execute();
        $resp = array();
        $info = array();
        while($row = $stmt->fetch()){
            $info['user_id'] = $row['id'];
            $info['user_name'] = $row['name'];
            array_push($resp, $info);

    }
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}catch(PDOException $e){
        echo '出错：'.$e->getMessage();
    }
}