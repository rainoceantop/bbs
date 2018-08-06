<?php
require '../../database/database.php';
$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);


$thread = array();
$thread['forum_id'] = $data['forum_id'];
$thread['title'] = $data['thread_title'];
$thread['body'] = $data['thread_body'];
$thread['head'] = $data['thread_head'];


$pdo = new Database();
$conn = $pdo->connect();

$sql = 'insert into threads(forum_id, thread_title, thread_body, thread_head) value(:forum, :title, :body, :head);';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':forum', $thread['forum_id']);
$stmt->bindParam(':title', $thread['title']);
$stmt->bindParam(':body', $thread['body']);
$stmt->bindParam(':head', $thread['head']);
try {
    $stmt->execute();
    echo $conn->lastInsertId();
} catch(PDOException $e) {
    exit('insert error:'.$e->getMessage());
}
