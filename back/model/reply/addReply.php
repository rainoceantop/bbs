<?php
require '../../database/database.php';

$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);
$dataKeys = implode(',',array_keys($data));
$dataVals = array_values($data);


$pdo = new Database();
$conn = $pdo->connect();

$sql = 'insert into replies('.$dataKeys.') value(?,?,?,?,?)';
$stmt = $conn->prepare($sql);
try{
    $stmt->execute($dataVals);
    echo 'SUCCESS';
} catch(PDOException $e){
    echo 'ERROR:'.$e->getMessage();
}
