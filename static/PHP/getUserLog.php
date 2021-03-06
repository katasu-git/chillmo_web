<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
require_once(dirname(__FILE__) . "/../PHP/getUser.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function createUser($userId, $today, $pdo) {
    # $pdo = connectMysql();
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

function writeUseMyPage($userId, $pdo) {
    # $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    line_conversations (line_user_id, reply_action) 
    VALUES (:line_user_id, :reply_action)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':reply_action', "check-mypage", PDO::PARAM_STR);
    $stmt->execute();

}

function mainFunc() {
    $today = date("Y-m-d", strtotime("now"));
    $userId = $_POST['line_user_id'];
    $user_data = getUser($userId);
    $pdo = connectMysql();

    if($user_data) {
        writeUseMyPage($userId, $pdo);
        return $user_data;

    } else {
        createUser($userId, $today, $pdo);
        writeUseMyPage($userId, $pdo);
        return getUser($userId);
    }
}

echo json_encode(mainFunc());