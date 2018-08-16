<?php
require '../../database/database.php';

$pdo = new Database();
$conn = $pdo->connect();
$symbol = $_GET['for'];

switch($symbol){
    case 'updateUserGroups':
        $action = $_GET['action'];
        $data = array(
            'group_id' => $_POST['group_id'],
            'users' => $_POST['users']
        );
        updateUserGroups($conn, $data, $action);
        break;
    case 'avatar':
        $user_id = $_GET['id'];
        $file = $_FILES['avatar'];
        $file_temp_name = $file['tmp_name'];
        $file_name = $file['name'];
        $user_url = 'imgs/'.$file_name;
        move_uploaded_file($file_temp_name, dirname(__FILE__, 4).'/front/imgs/'.$file_name);
        updateUserAvatar($conn, $user_url, $user_id);
        break;
}

function updateUserAvatar($conn, $url, $id){
    $sql = 'update users set avatar = :url where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "<script>;alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
}

function updateUserGroups($conn, $data, $action){
    $gid = $data['group_id'];
    $users = $data['users'];
    $ssql = 'select user_groups from users where id = :user_id';
    $usql = 'update users set user_groups = :user_groups where id = :user_id';
    try{
        foreach($users as $user){
            $stmt = $conn->prepare($ssql);
            $stmt->bindParam(':user_id', $user);
            $stmt->execute();
            //获取该用户的用户组
            $user_groups = $stmt->fetch()[0];
            //解析json
            $user_groups = json_decode($user_groups, true);
            if($action == 'add'){
                //将添加的用户组push进去
                array_push($user_groups, $gid);
            }
            if($action == 'remove'){
                //将添加的用户组移除
                array_splice($user_groups, array_search($gid, $user_groups), 1); 
            }
            //转成json
            $user_groups = json_encode($user_groups, JSON_UNESCAPED_UNICODE);
            //更新数据
            $s = $conn->prepare($usql);
            $s->bindParam(':user_groups', $user_groups);
            $s->bindParam(':user_id', $user);
            $s->execute();
        }
        echo "<script>;alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
    } catch(PDOException $e){
        echo '出错：'.$e->getMessage();
    }
}