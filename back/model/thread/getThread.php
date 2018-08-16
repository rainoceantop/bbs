<?php
session_start();
require '../../database/database.php';
require '../../utils/timeToHuman.php';

//数据库
$pdo = new Database();
$conn = $pdo->connect();
//时间
$t = new TimeToHuman();

$symbol = $_GET['for'];


switch($symbol){
    case "getHomeThreads":
        getHomeThreads($conn, $t);
        break;
    case "getForumThreads":
        $forum_id = $_GET['id'];
        getForumThreads($conn, $forum_id, $t);
        break;
    case "getThreadDetail":
        $thread_id = $_GET['id'];
        getThreadDetail($conn, $thread_id, $t);
        break;
    case "getUserThreads":
        $user_id = $_GET['id'];
        getUserThreads($conn, $user_id, $t);
        break;
    case "getHomeThreadsByTagId":
        $tag_id = $_GET['id'];
        getHomeThreadsByTagId($conn, $tag_id, $t);
        break;
}


//获取首页帖子
function getHomeThreads($conn, $t){
    getThreads($conn, NULL, 'homeThreads', $t);
}

//获取forum页帖子
function getForumThreads($conn, $forum_id, $t){
    getThreads($conn, $forum_id, 'forumThreads', $t);
}

//获取帖子详情
function getThreadDetail($conn, $thread_id, $t){
    getThreads($conn, $thread_id, 'threadDetail', $t);
}

//获取用户帖子
function getUserThreads($conn, $user_id, $t){
    getThreads($conn, $user_id, 'userThreads', $t);
}

//根据标签id获取帖子
function getHomeThreadsByTagId($conn, $tag_id, $t){
    getThreads($conn, $tag_id, 'homeThreadsByTagId', $t);
}

function getThreads($conn, $param, $symbol, $t){
    $resp = array();
    $data = array();
    $info = array();
    try{
        $conn -> beginTransaction();
        if($symbol == 'homeThreads'){
            $sql = "select * from threads where thread_is_filed = '0'";
            $stmt = $conn->prepare($sql);
        } else if($symbol == 'forumThreads'){
            $sql = "select * from threads where forum_id = :forum_id and thread_is_filed = '0'";
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
        } else if($symbol == 'homeThreadsByTagId'){
            $sql = "select * from threads, thread_tag_ref ttr where ttr.thread_id = threads.id and ttr.tag_id = :tag_id and thread_is_filed = '0'";
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
            //格式化时间
            $info['posted_time'] = $t->init($row['thread_created_at'])->format();

            $info['is_filed'] = $row['thread_is_filed'];
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
                if(!empty($rd)){
                    $info['replied_user'] = $rd[0];
                    $info['replied_time'] = $t->init($rd[1])->format();
                } else {
                    $info['replied_user'] = '无';
                    $info['replied_time'] = '';
                }
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

