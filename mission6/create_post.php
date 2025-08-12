<?php
  include_once "dbconnect.php";
  $pdo = db();
  
  //id、投稿したユーザーのid、文章、投稿画像、投稿日時のテーブルを作る。
  //user_idとaccountのidを一致させる。
  $sql = "CREATE TABLE IF NOT EXISTS post"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "user_id INT NOT NULL,"
        . "text TEXT,"
        . "image VARCHAR(255),"
        . "created DATETIME DEFAULT CURRENT_TIMESTAMP,"
        . "FOREIGN KEY (user_id) REFERENCES account(id)"
        .");";
 
  $stmt = $pdo->query($sql);
?>