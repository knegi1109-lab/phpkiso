<?php
  include_once "dbconnect.php";
  $pdo = db();
  
  //ぶっこわす
  $sql = 'DROP TABLE post';
  $stmt = $pdo->query($sql);
?>