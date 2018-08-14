<?php
require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();
$sql = 'delete from threads where id=6';
$stmt = $conn->prepare($sql);
$stmt->execute();