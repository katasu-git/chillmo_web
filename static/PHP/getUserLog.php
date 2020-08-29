<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
require_once(dirname(__FILE__) . "/../PHP/getUser.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

/*
function getUser($userId) {
    $pdo = connectMysql();
    $sql = "SELECT * FROM chillmo_user WHERE line_user_id='$userId'";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_info = array('id' => $row['id'], 'line_user_id' => $row['line_user_id'], 'check_sum' => $row['check_sum'], 'check_weekly' => $row['check_weekly'], 'check_continue' => $row['check_continue'], 'answer_sum' => $row['answer_sum'], 'answer_correct' => $row['answer_correct'], 'created_at' => $row['created_at'], 'last_check_date' => $row['last_check_date']);
        array_push($result, $user_info);
    }
    return $result;
}
*/

function createUser($userId, $today) {
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    chillmo_user (line_user_id, check_sum, check_weekly, check_continue, answer_sum, answer_correct, created_at, last_check_date) 
    VALUES (:line_user_id, :check_sum, :check_weekly, :check_continue, :answer_sum, :answer_correct, :created_at, :last_check_date)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':check_sum', 0, PDO::PARAM_INT);
    $stmt->bindValue(':check_weekly', 0, PDO::PARAM_INT);
    $stmt->bindValue(':check_continue', 0, PDO::PARAM_INT);
    $stmt->bindValue(':answer_sum', 0, PDO::PARAM_INT);
    $stmt->bindValue(':answer_correct', 0, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $today, PDO::PARAM_STR);
    $stmt->bindValue(':last_check_date', $today, PDO::PARAM_STR);
    $stmt->execute();
}

function mainFunc() {
    $today = date("Y-m-d", strtotime("now"));
    $userId = $_POST['line_user_id'];
    $user_data = getUser($userId);

    if($user_data) {
        return $user_data;

    } else {
        createUser($userId, $today);
        return getUser($userId);
    }
}

echo json_encode(mainFunc());