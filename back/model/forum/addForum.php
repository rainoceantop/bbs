<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();

$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);


$forum = array();
$forum['forum_name'] = $data['forum_name'];

$sql = 'insert into forums value(NULL, :name)';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':name', $forum['forum_name']);
try{
    $stmt->execute();
    echo 'SUCCESS';
} catch(PDOException $e){
    echo 'ERROR:'.$e->getMessage();
}