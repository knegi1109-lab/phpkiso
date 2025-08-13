<?php
session_start();

// 未ログインならリダイレクト
if (!isset($_SESSION["user_id"])) 
{
    header("Location: login.php");
    exit;
}

// sql接続
include_once "dbconnect.php";
$pdo = db();
?>

<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>投稿画面</title>
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
                <a href="timeline.php" style="color:white;">←タイムラインへ戻る</a>
            </div>
        </strong>
    </div>
    
    <p id="site-title" style="text-align:center; font-weight:bold;">
        投稿画面
    </p>
    
    <!--投稿フォーム-->
    <form method="post" enctype="multipart/form-data" class="center">
        <textarea name="text" placeholder="投稿内容を書いてね" rows="5" cols="60"></textarea><br>
        <input type="file" name="image"><br>
        <input type="submit" name="submit" value="投稿する" class="submit-btn">
    </form>
    
    <?php
      //提出の時点で文か写真があった場合投稿
      if (isset($_POST["submit"]) && (!empty($_POST["text"]) || !empty($_FILES["image"]["name"])))
      {
          $text = $_POST["text"];
          $user_id = $_SESSION["user_id"];
          
           $image_name = "";//写真ファイルの初期名前
           
           //写真を新しい名前で登録しuploadsフォルダーに入れる。
           if (!empty($_FILES["image"]["name"]))
           {
               $image_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
               move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image_name);
           }
           
          //投稿してタイムラインに遷移
          $sql = "INSERT INTO post (user_id, text, image) VALUES (:user_id, :text, :image)";
          $stmt = $pdo->prepare($sql);
          $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
          $stmt->bindValue(":text", $text, PDO::PARAM_STR);
          $stmt->bindValue(":image", $image_name, PDO::PARAM_STR);
          $stmt->execute();
          
          header("Location: timeline.php");
          exit;
      }
   ?>
</body>
</html>