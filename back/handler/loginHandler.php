<?php
session_start();
require '../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
//判断登录/注销操作
$symbol = isset($_GET['log']);

//登录操作
if(!$symbol){
    login($conn);
} else {
    if($_GET['log'] == '1'){
        logout();

    }
    else if($_GET['log'] == '2'){
        checkLog();
    }
}

function login($conn){

    $data = file_get_contents('php://input');
    $data = json_decode($data, TRUE);
    $username = $data['username'];
    $password = $data['password'];
    $message = '';
    
    $sql = 'select id, username, password, name, avatar, is_admin from users where username=:username limit 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    try{
        $stmt->execute();
        if($stmt->rowCount()){
            $info = $stmt->fetch();
            if($info['password'] == hash("sha256",$password)){
                $message = 'SUCCESS';
                $_SESSION['id'] = $info['id'];
                $_SESSION['user'] = $info['name'];
                $_SESSION['avatar'] = $info['avatar'];
                $_SESSION['is_admin'] = $info['is_admin'];
                //更新最后登录时间
                $sql = 'update users set last_online = now() where users.id = :user_id';
                $s = $conn->prepare($sql);
                $s->bindParam(':user_id', $info['id']);
                $s->execute();
            } else {
                $message = 'ERROR';
            }
        } else {
            $message = 'ERROR';
        }
    } catch(PDOException $e){
        echo '出错：'.$e->getMessage();
    }
    echo $message;
}

function logout(){
    unset($_SESSION['user']);
    echo 'SUCCESS';
}

function checkLog(){
    $info = array();
    if(isset($_SESSION['user'])){
        $info['is_login'] = TRUE;
        $info['id'] = $_SESSION['id'];
        $info['user'] = $_SESSION['user'];
        $info['avatar'] = $_SESSION['avatar'];
        $info['is_admin'] = $_SESSION['is_admin'];
    }
    else{
        $info['is_login'] = FALSE;
    }
    $resp_data = json_encode($info, JSON_UNESCAPED_UNICODE);
    echo $resp_data;
}