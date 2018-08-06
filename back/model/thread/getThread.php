<?php
session_start();
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case "getHomeThreads":
        getHomeThreads($conn);
        break;
    case "getForumThreads":
        $forum_id = $_GET['id'];
        getForumThreads($conn, $forum_id);
        break;
    case "getThreadDetail":
        $thread_id = $_GET['id'];
        getThreadDetail($conn, $thread_id);
        break;
}


//获取首页帖子
function getHomeThreads($conn){
    $sql = 'select * from threads';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    fetch_data($stmt, 'threads');
}

//获取forum页帖子
function getForumThreads($conn, $forum_id){
    $sql = 'select * from threads where forum_id = :forum_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->execute();
    fetch_data($stmt, 'threads');
}

//获取帖子详情
function getThreadDetail($conn, $thread_id){
    $sql = 'select * from threads where id = :thread_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->execute();
    fetch_data($stmt, 'detail');
}

//获取数据
function fetch_data($stmt, $symbol){
    $resp = array();
    $data = array();
    $info = array();
    //暂未有user，先使用自定义数据
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
    while($row = $stmt->fetch()) {
        $info['thread_id'] = $row['id'];
        $info['forum_id'] = $row['forum_id'];
        $info['avatar'] = $user_avatar[array_rand($user_avatar)];
        $info['thread_title'] = $row['thread_title'];
        $info['thread_head'] = $row['thread_head'];
        $info['posted_time'] = $row['thread_created_at'];
        if($symbol == 'detail'){
            $info['thread_body'] = $row['thread_body'];
            $info['views'] = random_int(1, 200000);
        } else {
            $info['replied_user'] = $replied_user[array_rand($replied_user)];
            $info['replied_time'] = '2018-07-05 15:12:04';
        }
        array_push($data, $info);
    }
    array_push($resp, $data);
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}

