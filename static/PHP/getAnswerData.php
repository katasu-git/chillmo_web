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
        $user_info = array('id' => $row['id'], 'line_user_id' => $row['line_user_id'], 'check_sum' => $row['check_sum'], 'check_weekly' => $row['check_weekly'], 'check_continue' => $row['check_continue'], 'answer_sum' => $row['answer_sum'], 'answer_correct' => $row['answer_correct'], 'created_at' => $row['created_at'], 'last_check_date' => $row['last_check_date']);
        array_push($result, $user_info);
    }
    return $result[0];
}

function insertUser($userId, $isCorrect, $today) {
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    chillmo_user (line_user_id, answer_sum, answer_correct, created_at, last_check_date) 
    VALUES (:line_user_id, :answer_sum, :answer_correct, :created_at, :last_check_date)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':answer_sum', 1, PDO::PARAM_INT);
    if($isCorrect == "true") {
        $stmt->bindValue(':answer_correct', 1, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(':answer_correct', 0, PDO::PARAM_INT);
    }
    $stmt->bindValue(':created_at', $today, PDO::PARAM_STR);
    $stmt->bindValue(':last_check_date', $today, PDO::PARAM_STR);
    $stmt->execute();
}

function countUp($userId, $isCorrect) {
    $pdo = connectMysql();
    if($isCorrect == "true") {
        $sql = "UPDATE chillmo_user SET answer_sum = answer_sum + 1, answer_correct = answer_correct + 1 WHERE line_user_id = '$userId'";
    } else {
        $sql = "UPDATE chillmo_user SET answer_sum = answer_sum + 1 WHERE line_user_id = '$userId'";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function getAnswerData() {
    $userId = $_POST['userId'];
    $isCorrect = $_POST['isCorrect'];
    $user_data = getUser($userId);
    $today = date("Y-m-d", strtotime("now"));

    if($user_data) {
        countUp($userId, $isCorrect);

    } else {
        insertUser($userId, $isCorrect, $today);
    }

    $result = getUser($userId);
    return $result;
}

echo json_encode(getAnswerData());