<?php
    $message="重複している名前は使えないから気を付けて！";//初期メッセージ
    if(isset($_POST["submit"]))
    {
        //新規登録が押されたらsql接続開始
        include_once "dbconnect.php";
        $pdo = db();
        
        $name = $_POST["username"];
        $pass = $_POST["pass"];
        
        //入力された名前と同じアカウントを数える
        $sql = "SELECT COUNT(*) FROM account WHERE name = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        //あった場合
        if($count>0)
        {
            $message="このユーザー名は使われています<br />";//重複時メッセージ
        }
        
        //なかった場合、アカウントを追加してログイン画面へ
        else
        {
            if(!empty($_POST["username"]) && !empty($_POST["pass"]))
            {
                $sql = "INSERT INTO account (name, pass) VALUES (:name, :pass)";
                $stmt = $pdo->prepare($sql);
       
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->execute();
                header("Location: https://tech-base.net/tb-270313/mission6/login.php");
                exit;
            }
      
            else
            {
                $message="入力不備あり<br />";//入力不足時メッセージ
            }
        }
    }
    ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>アカウント作成画面</title>
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
        <srrong>
            <div class="headright">
                <a href="login.php" style="color:white;">ログイン</a>
                <a href="csvin.php" style="color:white;">団体登録</a>
            </div>
        </srrong>
    </div>
    
    <div class="center" id="site-title" style="font-size:20px; text-align:center; font-weight:bold;">
        新規登録
    </div>
    
    <!--新規登録フォーム-->
    <form action="" method="post">
        <div class="center">
            <input type="text" size="100" name="username" placeholder="ユーザー名">
            <br>
            <input type="password" size="100" name="pass" placeholder="パスワード">
            <br>
            <input type="submit" name="submit" value="新規登録">
        </div>
    </form>
    <?php echo $message; ?><!--状況別メッセージ-->
</body>
</html>