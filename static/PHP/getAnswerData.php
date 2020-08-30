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
    return $result[0];
}
*/

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

function writeAnswer($user_id, $rumorId, $isCorrect) {
    
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    line_conversations (line_user_id, reply_action, user_message_type, user_message, reply_rumor) 
    VALUES (:line_user_id, :reply_action, :user_message_type, :user_message, :reply_rumor)");
    $stmt->bindValue(':line_user_id', $user_id, PDO::PARAM_STR);
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

    if($user_data) {
        countUp($userId, $isCorrect);

    } else {
        insertUser($userId, $isCorrect, $today);
    }

    writeAnswer($userId, $rumorId, $isCorrect); # 回答した問題を記録

    $result = getUser($userId)[0];
    return $result;
}

echo json_encode(getAnswerData());