<?php
require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'addUser':
    $user = array(
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'name' => $_POST['user'],
    );
    if(!isset($_POST['user_groups'])){
        $user['user_groups'] = [];
    } else {
        $user['user_groups'] = $_POST['user_groups'];
    }
    addUser($conn, $user);
    break;
    case 'addUserGroup':
    $user_group = array(
        'user_group_name' => $_POST['user_group_name'],
        'rights' => $_POST['rights']
    );
    addUserGroup($conn, $user_group);
    break;
}


function addUser($conn, $user){
    $sql = 'insert into users(username, password, name, user_groups) value(:username, :password, :name, :user_groups)';
    
    $user_groups = json_encode($user['user_groups']);

    try{
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $user['username']);
    $stmt->bindParam(':password', $user['password']);
    $stmt->bindParam(':name', $user['name']);
    $stmt->bindParam(':user_groups', $user_groups);
    $stmt->execute();
    echo "<script>;alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
    }catch(PDOException $e){
        echo ' 出错：'.$e->getMessage();
    }
}
function addUserGroup($conn, $user_group){
    $sql = 'insert into user_groups(user_group_name, rights) value(:user_group_name, :rights)';
    $rights = json_encode($user_group['rights']);
    try{
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_group_name', $user_group['user_group_name']);
    $stmt->bindParam(':rights', $rights); 
    $stmt->execute();
    echo "<script>;alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
    }catch(PDOException $e){
        echo ' 出错：'.$e->getMessage();
    }
}

