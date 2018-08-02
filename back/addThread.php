<?php
require 'database/database.php';

$thread = array();
$thread['title'] = $_POST['thread_title'];
$thread['body'] = $_POST['thread_body'];
$thread['head'] = $_POST['thread_head'];


$pdo = new Database();
$conn = $pdo->connect();

$sql = 'insert into threads(thread_title,thread_body,thread_head) value(:title, :body, :head);';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':title', $thread['title']);
$stmt->bindParam(':body', $thread['body']);
$stmt->bindParam(':head', $thread['head']);
try {
    $stmt->execute();
    header('location:../front/home.html');
} catch(PDOException $e) {
    exit('insert error:'.$e->getMessage());
}
