<?php
require 'database/database.php';

$pdo = new Database();
$conn = $pdo->connect();

$username = $_POST['username'];
$password = $_POST['password'];
$message = '';

$sql = 'select username, password from users where username=:username limit 1';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
try{
    $stmt->execute();
    if($stmt->rowCount()){
        if($stmt->fetch()['password'] == $password){
            $message = 'SUCCESS';
        } else {
            $message = 'ERROR';
        }
    }
} catch(PDOException $e){
    echo '出错：'.$e->getMessage();
}
echo $message;