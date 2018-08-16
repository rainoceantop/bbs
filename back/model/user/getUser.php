<?php
require '../../database/database.php';
require '../../utils/timeToHuman.php';

//数据库
$pdo = new Database();
$conn = $pdo->connect();
//时间
$t = new TimeToHuman();

$symbol = $_GET['for'];

switch($symbol){
    case 'getUserById':
        $user_id = $_GET['id'];
        getUserById($conn, $user_id, $t);
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
    case 'getUsers':
        $id = $_GET['id'];
        getUsers($conn, $id, $t);
        break;
}

//根据id查询用户
function getUserById($conn, $user_id, $t){
    $sql = 'select name, avatar, created_at, last_online, user_groups from users where id = :user_id';
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $resp = array();
    $info = array();
    while($row = $stmt->fetch()){
        $info['user'] = $row['name'];
        $info['avatar'] = $row['avatar'];
        $info['created_at'] = $t->init($row['created_at'])->onlyDate();
        $info['last_online'] = $t->init($row['last_online'])->onlyDate();

        //获取用户所属用户组的权限
        $groups = $row['user_groups'];
        $groups = json_decode($groups, true);
        $sql = 'select user_group_name from user_groups where id = :group_id';
        $s = $conn->prepare($sql);
        $user_groups = array();
        foreach($groups as $group){
            $s->bindParam(':group_id', $group);
            $s->execute();
            array_push($user_groups, $s->fetch()[0]);
        }
        $info['user_groups'] = implode(',', $user_groups);
        array_push($resp, $info);
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

//获取用户
function getUsers($conn, $id, $t){
    $sql = "select id, name, avatar, created_at, last_online, user_groups from users where is_admin = '0' and id != :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $resp = array();
    while($row = $stmt->fetch()){
        //保存用户信息进数组
        $info = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'avatar' => $row['avatar'],
            'created_at' => $t->init($row['created_at'])->onlyDate(),
            'last_online' => $t->init($row['last_online'])->onlyDate()
        );
        //获取用户所属用户组的权限
        $groups = $row['user_groups'];
        $groups = json_decode($groups, true);
        $sql = 'select user_group_name from user_groups where id = :group_id';
        $s = $conn->prepare($sql);
        $user_groups = array();
        if(!empty($groups)){
        foreach($groups as $group){
            $s->bindParam(':group_id', $group);
            $s->execute();
            array_push($user_groups, $s->fetch()[0]);
        }
        }
        $info['user_groups'] = implode(',', $user_groups);
        array_push($resp, $info);
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}