<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
require_once(dirname(__FILE__) . "/../PHP/getUser.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function resetTodayAnswer($userId, $today, $pdo) {
    # $pdo = connectMysql();
    $sql = "UPDATE chillmo_user SET answer_today = 0, answer_correct_today = 0, last_answer_date = '$today' WHERE line_user_id = '$userId'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

}

function countUp($userId, $isCorrect, $pdo) {
    # $pdo = connectMysql();
    if($isCorrect == "true") {
        $sql = "UPDATE chillmo_user SET answer_sum = answer_sum + 1, answer_correct = answer_correct + 1, answer_today = answer_today + 1, answer_correct_today = answer_correct_today + 1 WHERE line_user_id = '$userId'";
    } else {
        $sql = "UPDATE chillmo_user SET answer_sum = answer_sum + 1, answer_today = answer_today + 1 WHERE line_user_id = '$userId'";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

}

function writeAnswer($userId, $rumorId, $isCorrect, $pdo) {
    # $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    line_conversations (line_user_id, reply_action, user_message_type, user_message, reply_rumor) 
    VALUES (:line_user_id, :reply_action, :user_message_type, :user_message, :reply_rumor)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':reply_action', "answer-quiz", PDO::PARAM_STR);
    $stmt->bindValue(':user_message_type', "", PDO::PARAM_STR);
    $stmt->bindValue(':user_message', $isCorrect, PDO::PARAM_STR);
    $stmt->bindValue(':reply_rumor', $rumorId, PDO::PARAM_STR);
    $stmt->execute();

}

function getAnswerData() {
    $rumorId = $_POST['rumorId'];
    $userId = $_POST['userId'];
    $isCorrect = $_POST['isCorrect'];
    $user_data = getUser($userId)[0];
    $today = date("Y-m-d", strtotime("now"));
    $pdo = connectMysql();

    if($user_data['last_answer_date'] != $today) {
        resetTodayAnswer($userId, $today, $pdo);
    }

    countUp($userId, $isCorrect, $pdo);
    writeAnswer($userId, $rumorId, $isCorrect, $pdo); # なんか重い？
    $result = getUser($userId)[0];
    return $result;

}

echo json_encode(getAnswerData());