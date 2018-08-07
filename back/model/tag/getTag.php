<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'getTagGroups':
        getTagGroups($conn);
        break;
    case 'getTags':
        getTags($conn);
        break;
    case 'getThreadTags':
        $thread_id = $_GET['thread_id'];
        getThreadTags($conn, $thread_id);
        break;
    case 'getTagGroupsByForumId':
        $forum_id = $_GET['id'];
        getTagGroups($conn, $forum_id);
        break;
    case 'getTagsByTagGroupsId':
        $tag_group_id = $_GET['tag_group_id'];
        getTags($conn, $tag_group_id);
        break;
}


//获取标签组
function getTagGroupsByForumId($conn, $forum_id){
    $sql = 'select id, forum_id, tag_group_name from tag_groups where forum_id = :forum_id';
    $stmt = $conn -> prepare($sql);
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->execute();
    fetch_data($stmt, 'groups');
}



//根据标签组id获取标签
function getTagsByTagGroupsId($conn, $tag_group_id){
    $sql = 'select id, forum_id, tag_group_id, tag_name from tags where tag_group_id = :tag_group_id';
    $stmt = $conn -> prepare($sql);
    $stmt->bindParam(':tag_group_id', $tag_group_id);
    $stmt->execute();
    fetch_data($stmt, 'tags');
}

//获取所有标签组
function getTagGroups($conn){
    $sql = 'select id, forum_id, tag_group_name from tag_groups';
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    fetch_data($stmt, 'groups');
}

//获取thread标签
function getThreadTags($conn,$thread_id){
    $sql = 'select thread_id, tag_name from tags, thread_tag_ref ttr where ttr.thread_id = :thread_id and tags.id = ttr.tag_id;';
    $stmt = $conn -> prepare($sql);
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->execute();
    fetch_data($stmt, 'threadTags');
}


//获取所有标签
function getTags($conn){
    $sql = 'select id, forum_id, tag_group_id, tag_name from tags';
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    fetch_data($stmt, 'tags');
}

//获取数据
function fetch_data($stmt, $symbol){
    $data = array();
    $resp = array();
    while($row = $stmt->fetch()){
            if($symbol == 'groups'){
                $data['tag_group_id'] =  $row['id'];
                $data['forum_id'] = $row['forum_id'];
                $data['tag_group_name'] = $row['tag_group_name'];
            }
            if($symbol == 'tags'){
                $data['tag_id'] = $row['id'];
                $data['forum_id'] =  $row['forum_id'];
                $data['tag_group_id'] = $row['tag_group_id'];
                $data['tag_name'] = $row['tag_name'];
            }
            if($symbol == 'threadTags'){
                $data['thread_id'] = $row['thread_id'];
                $data['tag_name'] = $row['tag_name'];
            }
        array_push($resp, $data);
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}