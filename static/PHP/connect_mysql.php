<?php
ini_set('display_errors',1);

function connectMysql() {
  $data = file_get_contents( dirname(__FILE__) . "/../conf/dbInfo.txt" );
  $data = explode( "/", $data );
  $dbname = $data[0];
  $userName = $data[1];
  $pass = $data[2];
  #var_dump($data);
  $pdo = new PDO(
      $dbname,
      $userName,
      $pass,
      [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]
  );
  return $pdo;
}

#connectMysql();