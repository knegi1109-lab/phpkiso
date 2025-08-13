<?php
 include_once "dbconnect.php";
 $pdo = db();
  
  //id、アカウント名、アカウントパスワード、アカウントアイコンのテーブルを作る。
  $sql = "CREATE TABLE IF NOT EXISTS likes"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "user_id INT NOT NULL,"
        . "post_id INT NOT NULL,"
        . "created DATETIME DEFAULT CURRENT_TIMESTAMP,"
        . "UNIQUE KEY unique_like (user_id, post_id)"
        .");";
 
  $stmt = $pdo->query($sql);
?>