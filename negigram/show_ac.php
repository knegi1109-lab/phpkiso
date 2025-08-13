<?php
     //sql接続
      include_once "dbconnect.php";
      $pdo = db();
      
      $sql = 'SELECT * FROM account';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
 
      foreach ($results as $row) 
      {
          echo $row['id'].'  ';
          echo $row['name'].'  ';
          echo $row['pass'].'<br />';
          echo $row['massege'].'<br />';
          echo "<hr>";
      }
?>