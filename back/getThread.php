<?php
session_start();
require 'database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$sql = 'select * from threads';
$single = isset($_GET['id']);

if($single){
    $sql = 'select * from threads where id ='.$_GET['id'];
}

$stmt = $conn->prepare($sql);
$stmt->execute();

$user_avatar = array(
    'imgs/1.jpg',
    'imgs/2.jpg',
    'imgs/3.jpg',
    'imgs/4.jpg',
    'imgs/5.jpg'
);
$replied_user = array(
    'Austin',
    '雨果',
    '亚历山大',
    '周树人',
    'David'
);

$resp = array();
$data = array();
$info = array();
$check = array('is_login' => FALSE);

while($row = $stmt->fetch()) {
    $info['thread_id'] = $row['id'];
    $info['avatar'] = $user_avatar[array_rand($user_avatar)];
    $info['thread_title'] = $row['thread_title'];
    $info['thread_head'] = $row['thread_head'];
    $info['posted_time'] = $row['thread_created_at'];
    if($single){
        $info['thread_body'] = $row['thread_body'];
        $info['views'] = random_int(1, 200000);
    } else {
        $info['replied_user'] = $replied_user[array_rand($replied_user)];
        $info['replied_time'] = '2018-07-05 15:12:04';
    }
    array_push($data, $info);
}
array_push($resp, $data);
$resp_data = json_encode($resp, JSON_UNESCAPED_UNICODE);

echo $resp_data;