<?php
require 'database/database.php';
$thread = array();
$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);


$thread['title'] = $data['thread_title'];
$thread['body'] = $data['thread_body'];
$thread['head'] = $data['thread_head'];


$pdo = new Database();
$conn = $pdo->connect();

$sql = 'insert into threads(thread_title,thread_body,thread_head) value(:title, :body, :head);';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':title', $thread['title']);
$stmt->bindParam(':body', $thread['body']);
$stmt->bindParam(':head', $thread['head']);
try {
    $stmt->execute();
    echo $conn->lastInsertId();
} catch(PDOException $e) {
    exit('insert error:'.$e->getMessage());
}
