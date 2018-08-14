<?php
require '../../database/database.php';
$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);


$thread = array();
$thread['forum_id'] = $data['forum_id'];
$thread['title'] = $data['thread_title'];
$thread['body'] = $data['thread_body'];
$thread['head'] = $data['thread_head'];
$thread['head_id'] = $data['user_id'];
$tags = $data['tags'];


$pdo = new Database();
$conn = $pdo->connect();



$thread_sql = 'insert into threads(forum_id, thread_title, thread_body, thread_head, head_id) value(:forum, :title, :body, :head, :user_id);';
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
$stmt->bindParam(':user_id', $thread['head_id']);
$stmt->execute();
//取出返回标签id，给thread_tag_ref
$thread_id = $conn->lastInsertId();
$stmt = $conn->prepare($tag_sql);
foreach($tags as $tag){
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->bindParam(':tag_id', $tag);
    $stmt->execute();
}


//更新日志
$log = file_get_contents('../../log/thread.log.json');
$log = json_decode($log, true);
$newThreadLog = array(
    'id' => $thread_id,
    'amount' => 0
);
array_push($log['views'], $newThreadLog);
$log = json_encode($log, JSON_UNESCAPED_UNICODE);
file_put_contents('../../log/thread.log.json', $log);
$conn->commit();
echo $thread_id;

} catch(PDOException $e) {
    $conn->rollBack();
    exit('insert error:'.$e->getMessage());
}
