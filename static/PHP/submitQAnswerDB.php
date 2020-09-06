<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function submitQAnswerDB() {
    
    $userId = $_POST['userId'];
    $qNum = $_POST['Qnum'];
    $whichSelect = '';
    $reasonText = '';

    if($_POST['dataStr']) {
        $whichSelect = $_POST['dataStr'];
    }
    if($_POST['reason']) {
        $reasonText = $_POST['reason'];
    }

    $pdo = connectMysql();
    $stmt = $pdo -> prepare("INSERT INTO 
    chillmo_enquete_0907 (userId, qNum, whichSelect, reasonText) 
    VALUES (:userId, :qNum, :whichSelect, :reasonText)");
    $stmt->bindValue('userId', $userId, PDO::PARAM_STR);
    $stmt->bindValue('qNum', "$qNum", PDO::PARAM_STR);
    $stmt->bindValue('whichSelect', $whichSelect, PDO::PARAM_STR);
    $stmt->bindValue('reasonText', $reasonText, PDO::PARAM_STR);

    $stmt->execute();

}

submitQAnswerDB();