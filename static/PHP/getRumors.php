<?php
ini_set('display_errors',1);
require_once(dirname(__FILE__) . "/../PHP/connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function getRumors() {
    
    $pdo = connectMysql();
    $sql = "SELECT * FROM rumors ORDER BY fix DESC LIMIT 200";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $rumorDetail = array('id' => $row['id'], 'content' => $row['content'], 'fix' => $row['fix'], 'fix_tweets' => $row['fix_tweets'], 'morpheme' => $row['morpheme'], 'updown' => $row['updown'], 'created_at' => $row['created_at']);
        array_push($result, $rumorDetail);
    }
    return $result;
}
echo json_encode(getRumors());

?>