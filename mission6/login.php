<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="style.css">
  
  <!--googlefontsからタイトルのフォントを引用した。-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playwrite+HU:wght@100..400&display=swap" rel="stylesheet">
</head>

<body>
     <!--style.cssでの設定でサイトのヘッダー真ん中に題名、右端に画面遷移の機能をつける。-->
    <div class="headbar">
        <div class="sitetitle">Negigram</div>
        <strong>
            <div class="headright">
                <a href="make_ac.php" style="color:white;">新規登録</a>
                <a href="csvin.php" style="color:white;">団体登録</a>
            </div>
        </strong>
    </div>
    
    <p id="site-title" style="text-align:center; font-weight:bold;">
    ログイン
    </p>
    
    <!--ログインフォーム-->
    <form action="" method="post">
        <div class="center">
            <input type="text" size="100" name="username" placeholder="ユーザー名">
            <br>
            <input type="password" size="100" name="pass" placeholder="パスワード">
            <br>
            <input type="submit" name="submit" value="ログイン">
            <br><br><br>
        </div>
    </form>
    
    
    <?php
      session_start();
      
      //ログインが押されたらsql接続開始
      if(isset($_POST["submit"]))
      {
          include_once "dbconnect.php";
          $pdo = db();
      
          if(!empty($_POST["username"]) && !empty($_POST["pass"]))
          {
              $name = $_POST["username"];
              $pass = $_POST["pass"];
              
              //データをnameにより探す。
              $sql = 'SELECT * FROM account WHERE name=:name';
              $stmt = $pdo->prepare($sql);

              $stmt->bindParam(':name', $name, PDO::PARAM_STR);

              $stmt->execute();
              $results = $stmt->fetchAll();
              
              //一致するユーザー名がない、つまりアカウントがまだない
              if (count($results) === 0)
              {
                  echo "アカウントが存在しません！<br /><br />";
              } 
              
              else
              {
                  foreach ($results as $row) 
                  {
                      if ($pass == $row['pass']) 
                      {
                          //ログインしたユーザーのid、名前をセッションに保存しタイムラインへ
                          $_SESSION["user_id"] = $row['id'];
                          $_SESSION["username"] = $row['name'];
                      
                          header("Location: timeline.php");
                          exit;
                      } 
                      
                      //passが合わない時
                      else 
                      {
                          echo "パスワードがちがう！！<br /><br />";
                      }
                  }
               }
           }
           
           //入力不備があるとき
           else
          {
              echo "入力不備あり<br />";
          }
      
      }
      
    ?>
</body>