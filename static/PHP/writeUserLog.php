<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
require_once(dirname(__FILE__) . "/../PHP/getUser.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function insertUser($userId, $today) {
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    chillmo_user (line_user_id, check_sum, check_weekly, check_continue, answer_sum, answer_correct, created_at, last_check_date) 
    VALUES (:line_user_id, :check_sum, :check_weekly, :check_continue, :answer_sum, :answer_correct, :created_at, :last_check_date)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':check_sum', 1, PDO::PARAM_INT);
    $stmt->bindValue(':check_weekly', 1, PDO::PARAM_INT);
    $stmt->bindValue(':check_continue', 1, PDO::PARAM_INT);
    $stmt->bindValue(':answer_sum', 0, PDO::PARAM_INT);
    $stmt->bindValue(':answer_correct', 0, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $today, PDO::PARAM_STR);
    $stmt->bindValue(':last_check_date', $today, PDO::PARAM_STR);
    $stmt->execute();
}

function countUp($userId) {
    $pdo = connectMysql();
    $sql = "UPDATE chillmo_user SET `check_sum` = check_sum + 1,  `check_weekly` = check_weekly + 1 WHERE line_user_id = '$userId'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function countupContinue($userId, $today, $yesterday, $lastCheck) {
    if($today == $lastCheck) {
        return;
    }
    if($lastCheck == $yesterday) {
        $pdo = connectMysql();
        $sql = "UPDATE chillmo_user SET check_continue = check_continue + 1, last_check_date = '$today' WHERE line_user_id = '$userId'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        // 一日以上経っている場合はカウントをリセット
        $pdo = connectMysql();
        $sql = "UPDATE chillmo_user SET check_continue = 1, last_check_date = '$today' WHERE line_user_id = '$userId'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    
}

function writeConversations($user_id, $rumorId) {
    
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    line_conversations (line_user_id, reply_action, reply_rumor) 
    VALUES (:line_user_id, :reply_action, :reply_rumor)");
    $stmt->bindValue(':line_user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindValue(':reply_action', "click-detail-link", PDO::PARAM_STR);
    $stmt->bindValue(':reply_rumor', $rumorId, PDO::PARAM_STR);

    $stmt->execute();

}

function writeUserLog() {
    $rumorId = $_POST['rumorId'];
    $userId = $_POST['userId'];
    $today = date("Y-m-d", strtotime("now"));
    $user_data = getUser($userId)[0];
    $lastCheck = $user_data['last_check_date'];
    $yesterday = date("Y-m-d", strtotime("-1 day"));

    if($user_data) {
        countUp($userId);
        countupContinue($userId, $today, $yesterday, $lastCheck);

    } else {
        insertUser($userId, $today);
    }
    writeConversations($userId, $rumorId);
}

writeUserLog()

?>