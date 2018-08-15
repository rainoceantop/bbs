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
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo '用户组删除成功';
}
