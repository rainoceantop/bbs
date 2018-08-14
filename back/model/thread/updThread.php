<?php

require '../../database/database.php';


$pdo = new Database();
$conn = $pdo->connect();

$symbol = $_GET['for'];

if($symbol == 'edit'){

$data = file_get_contents('php://input');
$data = json_decode($data, TRUE);

$thread = array(
    'id' => $data['thread_id'],
    'title' => $data['thread_title'],
    'body' => $data['thread_body'],
    'reason' => $data['updated_reason']
);

$sql = 'update threads set thread_title=:title, thread_body=:body, updated_reason=:reason, updated_at = now() where id = :id';
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $thread['title']);
    $stmt->bindParam(':body', $thread['body']);
    $stmt->bindParam(':reason', $thread['reason']);
    $stmt->bindParam(':id', $thread['id']);
    $stmt->execute();
    echo 'SUCCESS';
} catch(PDOException $e){
    echo '出错：'.$e->getMessage();
}
}
else if($symbol == 'file'){
    $thread_id = $_GET['thread_id'];
    $sql = "update threads set thread_is_filed = '1' where id = :thread_id";
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':thread_id', $thread_id);
        $stmt->execute();
        echo 'SUCCESS';
    }catch(PDOException $e){
        echo 'ERROR:'.$e->getMessage();
    }
}
else if($symbol == 'views'){
    $id = $_GET['id'];
    $log = file_get_contents('../../log/thread.log.json');
    $log = json_decode($log, true);
    $views_log = $log['views'];
    for($i=0; $i<count($views_log); $i++){
        if($views_log[$i]['id'] == $id){
            $views_log[$i]['amount'] += 1;
        }
    }
    $log['views'] = $views_log;
    $log = json_encode($log, JSON_UNESCAPED_UNICODE);
    file_put_contents('../../log/thread.log.json', $log);
}

