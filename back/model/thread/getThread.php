<?php
session_start();
require '../../database/database.php';
require '../../utils/timeToHuman.php';

//数据库
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
    case "getCollectedThreads":
        $user_id = $_GET['id'];
        getCollectedThreads($conn, $user_id);
        break;
    case "getSearchThreads":
        $s = $_GET['s'];
        getSearchThreads($conn, $s);
        break;
}


//获取首页帖子
function getHomeThreads($conn){
    $sql = "select * from threads where thread_is_filed = '0' order by id desc";
    $stmt = $conn->prepare($sql);
    getThreads($conn, $stmt, 'homeThreads');
}

//获取forum页帖子
function getForumThreads($conn, $forum_id){
    $sql = "select * from threads where forum_id = :forum_id and thread_is_filed = '0' order by id desc";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':forum_id', $forum_id);
    getThreads($conn, $stmt, 'forumThreads');
}

//获取帖子详情
function getThreadDetail($conn, $thread_id){
    $sql = 'select * from threads where threads.id = :thread_id order by id desc';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':thread_id', $thread_id);
    getThreads($conn, $stmt, 'threadDetail');
}

//获取用户帖子
function getUserThreads($conn, $user_id){
    $sql = 'select * from threads where head_id = :user_id order by id desc';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    getThreads($conn, $stmt, 'userThreads');
}

//根据标签id获取帖子
function getHomeThreadsByTagId($conn, $tag_id){
    $sql = "select * from threads, thread_tag_ref ttr where ttr.thread_id = threads.id and ttr.tag_id = :tag_id and thread_is_filed = '0' order by id desc";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tag_id', $tag_id);
    getThreads($conn, $stmt, 'homeThreadsByTagId');
}

//获取收藏帖子
function getCollectedThreads($conn, $user_id){
    $sql = 'select collections from users where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user_c = json_decode($stmt->fetch()[0], true);
    //时间
    $t = new TimeToHuman();
    $resp = array();
    $data = array();
    $info = array();
    $conn->beginTransaction();
    foreach($user_c as $tid){
        $sql = 'select * from threads where id = :tid';
        $s = $conn->prepare($sql);
        $s->bindParam(':tid', $tid);
        $s->execute();
        $row = $s->fetch();
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
        //获取回复数
        $sql = 'select count(*) from replies where thread_id = :thread_id';
        $s = $conn->prepare($sql);
        $s->bindParam(':thread_id', $tid);
        $s->execute();
        $info['replies'] = $s->fetch()[0];       

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
}
//根据关键字搜索
function getSearchThreads($conn, $s){
    $sql = 'select * from threads where thread_title like "%":s"%"';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':s', $s);
    getThreads($conn, $stmt, 'searchThreads');
}

function getThreads($conn, $stmt, $symbol){
    //时间
    $t = new TimeToHuman();
    $resp = array();
    $data = array();
    $info = array();
    try{
        $conn -> beginTransaction();
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

                //获取回复数
                $sql = 'select count(*) from replies where thread_id = :thread_id';
                $s = $conn->prepare($sql);
                $s->bindParam(':thread_id', $row['id']);
                $s->execute();
                $info['replies'] = $s->fetch()[0];
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

