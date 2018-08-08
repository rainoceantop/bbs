<?php
require '../../database/database.php';

$user = array();
$user['username'] = $_POST['username'];
$user['password'] = $_POST['password'];
$user['name'] = $_POST['user'];

$sql = 'insert into users(username, password, name) value(:username, :password, :name)';
$pdo = new Database();
$conn = $pdo->connect();

try{
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $user['username']);
$stmt->bindParam(':password', $user['password']);
$stmt->bindParam(':name', $user['name']);
$stmt->execute();
echo '添加用户成功';
}catch(PDOException $e){
    echo ' 出错：'.$e->getMessage();
}
