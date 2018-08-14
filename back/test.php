<?php

$log = file_get_contents('log/thread.log.json');
$log = json_decode($log, true);
// $newThreadLog = [
//     'id' => 21,
//     'views' => 205
//     ];
// array_push($log, $newThreadLog);
for($i=0; $i<count($log); $i++){
    if($log[$i]['id'] == 20){
        $log[$i]['views'] = 121554;
    }
}
// foreach($log as $l){
//     if($l['id'] == 20){
//         $l['views'] = 123112;
//     }
// }
$log = json_encode($log, JSON_UNESCAPED_UNICODE);
file_put_contents('log/thread.log.json', $log);
