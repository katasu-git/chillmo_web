<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function getUser($userId) {
    $pdo = connectMysql();
    $sql = "SELECT * FROM chillmo_user WHERE line_user_id='$userId'";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_info = array('id' => $row['id'], 'line_user_id' => $row['line_user_id'], 'check_sum' => $row['check_sum'], 
        'check_weekly' => $row['check_weekly'], 'check_continue' => $row['check_continue'], 'answer_sum' => $row['answer_sum'], 
        'answer_correct' => $row['answer_correct'], 'created_at' => $row['created_at'], 'last_check_date' => $row['last_check_date'],
        'gender' => $row['gender'], 'test_group' => $row['test_group']);
        array_push($result, $user_info);
    }
    return $result;
}