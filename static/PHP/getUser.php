<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function getUser($userId) {
    $pdo = connectMysql();
    $sql = "SELECT * FROM chillmo_user WHERE line_user_id='$userId'";
    $stmt = $pdo -> query($sql) -> fetchAll();
    return $stmt;
}