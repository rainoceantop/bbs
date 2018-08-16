<?php
require '../../database/database.php';
require '../../utils/timeToHuman.php';

//数据库
$pdo = new Database();
$conn = $pdo->connect();
//时间
$t = new TimeToHuman();

$symbol = $_GET['for'];

switch($symbol){
    case 'getRepliesByThreadId':
    $thread_id = $_GET['thread_id'];
    getRepliesByThreadId($conn, $thread_id, $t);
    break;
}


function getRepliesByThreadId($conn, $thread_id, $t){
    $info = array();
    $resp = array();
    //获取帖子的评论
    $sql = 'select * from replies where thread_id = :thread_id';
    
    $conn->beginTransaction();
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->execute();
    while($row = $stmt->fetch()){
        $info['replied_id'] = $row['id'];
        $info['replied_body'] = $row['replied_body'];
        $info['from_user_id'] = $row['from_user_id'];
        $info['to_user_id'] = $row['to_user_id'];
        $info['replied_index'] = $row['replied_index'];
        //格式化时间
        $info['replied_time'] = $t->init($row['replied_time'])->format();
        //获取fromuserid信息
        $sql = 'select name, avatar from users where id = :user_id';
        $s = $conn->prepare($sql);
        $s->bindParam(':user_id', $row['from_user_id']);
        $s->execute();
        $from_user_info = $s->fetch();
        $info['from_user_name'] = $from_user_info[0];
        $info['from_user_avatar'] = $from_user_info[1];
        // 如果回复的是评论，获取touserid信息
        if($row['replied_index']){
            $sql = 'select name, avatar from users where id = :user_id';
            $s = $conn->prepare($sql);
            $s->bindParam(':user_id', $row['to_user_id']);
            $s->execute();
            $to_user_info = $s->fetch();
            $info['to_user_name'] = $to_user_info[0];
            $info['to_user_avatar'] = $to_user_info[1];
            //获取回复的回复内容
            $sql = 'select replied_body from replies where id = :reply_id';
            $s = $conn->prepare($sql);
            $s->bindParam(':reply_id', $row['replied_index']);
            $s->execute();
            $info['to_user_replied'] = $s->fetch()[0];
        }
        array_push($resp, $info);
    }
    $conn->commit();
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}