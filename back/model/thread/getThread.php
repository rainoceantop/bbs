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
    case "getUserThreads":
        $user_id = $_GET['id'];
        getUserThreads($conn, $user_id);
        break;
    case "getHomeThreadsByTagId":
        $tag_id = $_GET['id'];
        getHomeThreadsByTagId($conn, $tag_id);
        break;
}


//获取首页帖子
function getHomeThreads($conn){
    getThreads($conn, NULL, 'homeThreads');
}

//获取forum页帖子
function getForumThreads($conn, $forum_id){
    getThreads($conn, $forum_id, 'forumThreads');
}

//获取帖子详情
function getThreadDetail($conn, $thread_id){
    getThreads($conn, $thread_id, 'threadDetail');
}

//获取用户帖子
function getUserThreads($conn, $user_id){
    getThreads($conn, $user_id, 'userThreads');
}

//根据标签id获取帖子
function getHomeThreadsByTagId($conn, $tag_id){
    getThreads($conn, $tag_id, 'homtThreadsByTagId');
}

function getThreads($conn, $param, $symbol){
    $resp = array();
    $data = array();
    $info = array();
    try{
        $conn -> beginTransaction();
        if($symbol == 'homeThreads'){
            $sql = 'select * from threads';
            $stmt = $conn->prepare($sql);
        } else if($symbol == 'forumThreads'){
            $sql = 'select * from threads where forum_id = :forum_id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':forum_id', $param);
        } else if($symbol == 'threadDetail'){
            $sql = 'select * from threads where threads.id = :thread_id;';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':thread_id', $param);
        } else if($symbol == 'userThreads'){
            $sql = 'select * from threads where head_id = :user_id;';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $param);
        } else if($symbol == 'homtThreadsByTagId'){
            $sql = 'select * from threads, thread_tag_ref ttr where ttr.thread_id = threads.id and ttr.tag_id = :tag_id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tag_id', $param);
        }
        $stmt->execute();
        while($row = $stmt->fetch()) {
            $info['thread_id'] = $row['id'];
            $info['forum_id'] = $row['forum_id'];
            //获取用户头像
            $avatar = $conn->query('select avatar from users where id = '.$row['head_id']);
            $info['avatar'] = $avatar->fetch()[0];
            $info['thread_title'] = $row['thread_title'];
            $info['thread_head'] = $row['thread_head'];
            $info['posted_time'] = $row['thread_created_at'];
            if($symbol == 'threadDetail'){
                $info['head_id'] = $row['head_id'];
                $info['thread_body'] = $row['thread_body'];
                $info['views'] = random_int(1, 200000);
            } else {
                //获取最后回复人的名称和回复时间
                $sql = 'select name,last_replied_time from users, threads where users.id = threads.last_replied_user and threads.id = :thread_id';
                $s = $conn->prepare($sql);
                $s->bindParam(':thread_id', $row['id']);
                $s->execute();
                $rd = $s->fetch();
                $info['replied_user'] = $rd[0];
                $info['replied_time'] = $rd[1];
            }

            //获取帖子标签
            $sql = 'select id, tag_name from tags, thread_tag_ref ttr where ttr.thread_id = :thread_id and tags.id = ttr.tag_id;';
            $s = $conn->prepare($sql);
            $s->bindParam(':thread_id', $row['id']);
            $s->execute();
            $info['tags'] = array();
            $tag_item = array();
            while($row = $s->fetch()){
                $tag_item['id'] = $row['id'];
                $tag_item['name'] = $row['tag_name'];
                array_push($info['tags'], $tag_item);
            }
            array_push($data, $info);
        }
        $conn->commit();
        array_push($resp, $data);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    } catch(PDOException $e){
        $conn->rollBack();
        echo '出错：'.$e->getMessage();
    }
}

