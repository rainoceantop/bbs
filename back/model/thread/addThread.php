<?php
require '../../database/database.php';
$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);


$thread = array();
$thread['forum_id'] = $data['forum_id'];
$thread['title'] = $data['thread_title'];
$thread['body'] = $data['thread_body'];
$thread['head'] = $data['thread_head'];
$tags = $data['tags'];


$pdo = new Database();
$conn = $pdo->connect();



$thread_sql = 'insert into threads(forum_id, thread_title, thread_body, thread_head) value(:forum, :title, :body, :head);';
$tag_sql = 'insert into thread_tag_ref values(:thread_id, :tag_id)';

try {
    //开始事务，添加thread和tag
$conn->beginTransaction();

//先添加thread
$stmt = $conn->prepare($thread_sql);
$stmt->bindParam(':forum', $thread['forum_id']);
$stmt->bindParam(':title', $thread['title']);
$stmt->bindParam(':body', $thread['body']);
$stmt->bindParam(':head', $thread['head']);
$stmt->execute();
//取出返回标签id，给thread_tag_ref
$thread_id = $conn->lastInsertId();
$stmt = $conn->prepare($tag_sql);
foreach($tags as $tag){
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->bindParam(':tag_id', $tag);
    $stmt->execute();
}
$conn->commit();
echo $thread_id;

} catch(PDOException $e) {
    $conn->rollBack();
    exit('insert error:'.$e->getMessage());
}
