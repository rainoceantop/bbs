<?php
require 'database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$sql = 'select * from threads';
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

while($row = $stmt->fetch()) {
    $data['avatar'] = $user_avatar[array_rand($user_avatar)];
    $data['thread_title'] = $row['thread_title'];
    $data['thread_head'] = $row['thread_head'];
    $data['posted_time'] = $row['thread_created_at'];
    $data['replied_user'] = $replied_user[array_rand($replied_user)];
    $data['replied_time'] = '2018-07-05 15:12:04';
    array_push($resp, $data);
}

$resp_data = json_encode($resp, JSON_UNESCAPED_UNICODE);

echo $resp_data;