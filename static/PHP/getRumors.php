<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function getRumors() {
    $today = date("Y-m-d");
    $weekday = date("Y-m-d",strtotime("-7 day"));
    
    $pdo = connectMysql();
    $sql = "SELECT * FROM rumors WHERE created_at BETWEEN '$weekday' AND '$today' ORDER BY fix DESC LIMIT 200";
    $stmt = $pdo -> query($sql) -> fetchAll();
    return $stmt;
}
echo json_encode(getRumors());

?>