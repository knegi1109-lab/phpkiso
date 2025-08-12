<?php
  include_once "dbconnect.php";
  $pdo = db();
  
  //ぶっこわす
  $sql = 'DROP TABLE account';
  $stmt = $pdo->query($sql);
?>