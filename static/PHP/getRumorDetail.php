<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function getRumorDetail() {
    $rumorId = $_POST['rumorId'];

    $pdo = connectMysql();
    $sql = "SELECT * FROM rumors WHERE id=$rumorId";
    $stmt = $pdo -> query($sql) -> fetchAll();
    return $stmt;
}
echo json_encode(getRumorDetail());

?>