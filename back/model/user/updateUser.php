<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];
$action = $_GET['action'];

switch($symbol){
    case 'updateUserGroups':
        $data = array(
            'group_id' => $_POST['group_id'],
            'users' => $_POST['users']
        );
        updateUserGroups($conn, $data, $action);
        break;
}

function updateUserGroups($conn, $data, $action){
    $gid = $data['group_id'];
    $users = $data['users'];
    $ssql = 'select user_groups from users where id = :user_id';
    $usql = 'update users set user_groups = :user_groups where id = :user_id';
    try{
        foreach($users as $user){
            $stmt = $conn->prepare($ssql);
            $stmt->bindParam(':user_id', $user);
            $stmt->execute();
            //获取该用户的用户组
            $user_groups = $stmt->fetch()[0];
            //解析json
            $user_groups = json_decode($user_groups, true);
            if($action == 'add'){
                //将添加的用户组push进去
                array_push($user_groups, $gid);
            }
            if($action == 'remove'){
                //将添加的用户组移除
                array_splice($user_groups, array_search($gid, $user_groups), 1); 
            }
            //转成json
            $user_groups = json_encode($user_groups, JSON_UNESCAPED_UNICODE);
            //更新数据
            $s = $conn->prepare($usql);
            $s->bindParam(':user_groups', $user_groups);
            $s->bindParam(':user_id', $user);
            $s->execute();
        }
            echo 'SUCCESS';
    } catch(PDOException $e){
        echo '出错：'.$e->getMessage();
    }
}