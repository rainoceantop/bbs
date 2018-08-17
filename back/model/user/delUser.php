<?php
require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'deleteUser':
        $id = $_GET['id'];
        deleteUser($conn, $id);
        break;
    case 'deleteGroup':
        $id = $_GET['id'];
        deleteGroup($conn, $id);
        break;
}

function deleteUser($conn, $id){
    $sql = 'delete from users where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo '用户删除成功';
}
function deleteGroup($conn, $id){
    $sql = 'delete from user_groups where id = :id';
    $stmt = $conn->prepare($sql);
    try{
        $conn->beginTransaction();
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        //移除改组中的所有用户
        $sql = 'select id, user_groups from users';
        $s = $conn->prepare($sql);
        $s->execute();
        $users = $s->fetchAll();
        foreach($users as $user){
            $user_groups = json_decode($user['user_groups'], true);
            //如果存在被删用户组，移除用户组
            if(in_array($id, $user_groups)){
                array_splice($user_groups, array_search($id, $user_groups), 1); 
            }
            //更新用户
            $user_groups = json_encode($user_groups, JSON_UNESCAPED_UNICODE);
            $sql = 'update users set user_groups = :user_groups where id = :user_id';
            $s = $conn->prepare($sql);
            $s->bindParam(':user_groups', $user_groups);
            $s->bindParam(':user_id', $user['id']);
            $s->execute();
        }
        $conn->commit();
    } catch(PDOException $e){
        $conn->rollBack();
        echo '出错：'.$e->getMessage();
    }
    echo '用户组删除成功';
}
