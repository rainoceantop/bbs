<?php

require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
$sql = 'update threads set thread_title=:title where id=1';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':title', 'Hello world');
$stmt->execute();