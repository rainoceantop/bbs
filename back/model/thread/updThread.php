<?php

require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
$sql = 'update threads set thread_title=:title, thread_body=:body, updated_reason=:reason, updated_at = now() where id=56';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':title', 'Hello world');
$stmt->bindValue(':body', 'foo bar foo bar little star');
$stmt->bindValue(':reason', 'test this update');
$stmt->execute();
