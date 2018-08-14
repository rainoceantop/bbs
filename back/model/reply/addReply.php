<?php
require '../../database/database.php';

$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);
$dataKeys = implode(',',array_keys($data));
$dataVals = array_values($data);


$pdo = new Database();
$conn = $pdo->connect();

try{
    $conn->beginTransaction();
    $sql = 'insert into replies('.$dataKeys.') value(?,?,?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->execute($dataVals);
    //更新帖子得最后回复人
    $sql = 'update threads set last_replied_user = :from_user_id, last_replied_time = now() where id = :thread_id';
    $s = $conn->prepare($sql);
    $s->bindParam(':from_user_id', $data['from_user_id']);
    $s->bindParam(':thread_id', $data['thread_id']);
    $s->execute();
    $conn->commit();
    echo 'SUCCESS';
} catch(PDOException $e){
    echo 'ERROR:'.$e->getMessage();
}
