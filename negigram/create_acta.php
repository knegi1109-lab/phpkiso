<?php
 include_once "dbconnect.php";
 $pdo = db();
  
  //id、アカウント名、アカウントパスワード、アカウントアイコンのテーブルを作る。
  $sql = "CREATE TABLE IF NOT EXISTS account"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "pass TEXT,"
        . "icon VARCHAR(255),"
        . "message TEXT"
        .");";
 
  $stmt = $pdo->query($sql);
?>