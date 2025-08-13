<?php
  include_once "dbconnect.php";
  $pdo = db();
  
  //ぶっこわす
  $sql = 'DROP TABLE likes';
  $stmt = $pdo->query($sql);
?>