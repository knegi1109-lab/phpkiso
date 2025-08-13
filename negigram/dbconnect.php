<?php
  //sql接続をあらかじめ作っておく
  function db()
  {
      $dsn = 'mysql:dbname=****;host=localhost';
      $dbUser = '****';
      $dbPassword = '********';
      $pdo = new PDO($dsn, $dbUser, $dbPassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      
      return $pdo;
  }
?>