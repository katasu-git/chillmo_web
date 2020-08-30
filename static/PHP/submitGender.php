<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
require_once(dirname(__FILE__) . "/../PHP/getUser.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function createUser($userId, $today, $gender) {
    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    chillmo_user (line_user_id, check_sum, check_weekly, check_continue, answer_sum, answer_correct, created_at, last_check_date, gender) 
    VALUES (:line_user_id, :check_sum, :check_weekly, :check_continue, :answer_sum, :answer_correct, :created_at, :last_check_date, :gender)");
    $stmt->bindValue(':line_user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':check_sum', 1, PDO::PARAM_INT);
    $stmt->bindValue(':check_weekly', 1, PDO::PARAM_INT);
    $stmt->bindValue(':check_continue', 1, PDO::PARAM_INT);
    $stmt->bindValue(':answer_sum', 0, PDO::PARAM_INT);
    $stmt->bindValue(':answer_correct', 0, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $today, PDO::PARAM_STR);
    $stmt->bindValue(':last_check_date', $today, PDO::PARAM_STR);
    $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
    $stmt->execute();
}

function updateGender($userId, $gender) {
    $pdo = connectMysql();
    $sql = "UPDATE chillmo_user SET gender = '$gender' WHERE line_user_id = '$userId'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function mainFunc() {
    $today = date("Y-m-d", strtotime("now"));
    $userId = $_POST['line_user_id'];
    $gender = $_POST['gender'];
    $user_data = getUser($userId);

    if($user_data) {
        updateGender($userId, $gender);

    } else {
        createUser($userId, $today, $gender);

    }
    return getUser($userId);
}

echo json_encode(mainFunc());