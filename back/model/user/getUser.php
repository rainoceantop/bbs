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
}


//根据id查询用户
function getUserById($conn, $user_id){
    $sql = 'select name, avatar, created_at from users where id = :user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $info = array();

    while($row = $stmt->fetch()){
        $info['user'] = $row['name'];
        $info['avatar'] = $row['avatar'];
        $info['created_at'] = $row['created_at'];
    }
    echo json_encode($info, JSON_UNESCAPED_UNICODE);
}
