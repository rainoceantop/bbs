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
    case 'updateUserCollections':
        $user_id = $_GET['uid'];
        $thread_id = $_GET['tid'];
        $action = $_GET['action'];
        updateUserCollections($conn, $user_id, $thread_id, $action);
        break;
    case 'adminUser':
        $user_id = $_GET['id'];
        adminUser($conn, $user_id);
        break;
}

//更新用户头像
function updateUserAvatar($conn, $url, $id){
    $sql = 'update users set avatar = :url where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "<script>;alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
}

//更新用户所属用户组
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

//更新用户收藏
function updateUserCollections($conn, $uid, $tid){
    //获取现在的收藏
    $sql = 'select collections from users where id = :id';
    $stmt = $conn->prepare($sql);
    try{
            $stmt->bindParam(':id', $uid);
    $stmt->execute();
    $user_c = json_decode($stmt->fetch()[0], true);
    $msg = '';
    if(!in_array($tid, $user_c)){
        //将收藏的帖子push进去
        array_push($user_c, $tid);
        $msg = 'ADDED';
    } else{
        //将收藏的帖子移除
        array_splice($user_c, array_search($tid, $user_c), 1);
        $msg = 'REMOVED';
    }
    $user_c = json_encode($user_c, JSON_UNESCAPED_UNICODE);
    $sql = 'update users set collections = :c where id = :id';
    $s = $conn->prepare($sql);
    $s->bindParam(':c', $user_c);
    $s->bindParam(':id', $uid);
    $s->execute();
    } catch(PDOException $e){
        $msg = 'ERROR';
    }

    echo $msg;    
}
//将用户升级为管理员
function adminUser($conn, $uid){
    $sql = 'update users set is_admin = "1" where id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->execute();
    echo '操作成功，编号为'.$uid.'的用户已经升级为管理员';
}