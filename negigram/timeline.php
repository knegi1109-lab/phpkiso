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
    
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>投稿一覧</title>
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
                <a href="myac.php" style="color:white;">ユーザー確認</a>
                on：<?php echo htmlspecialchars($_SESSION["username"]); ?>
            </div>
        </strong>
    </div>
    
    <p id="site-title" style="text-align:center; font-weight:bold;">
        タイムライン
    </p>
  
  <!--style.cssでの設定で検索フォームを作る-->
  <div class="search-bar">
    <form action="" method="post">
      <input type="text" name="search" placeholder="ユーザー名で検索" />
      <input type="submit" value="🔍検索" />
    </form>
    <br><br>
    
    <form action="" method="post">
        <!--style.cssでの設定で投稿ボタンを作る-->
        <input type="submit" name="submit" value="投稿する" class="submit-btn">
    </form>
    <br>
    
    <h2>
        投稿一覧
    <h2>
  </div>

  <!--style.cssの設定で投稿の幅等きめる-->
  <div class="post-container">
  <?php
    // 検索キーワード取得
    $search = isset($_POST["search"]) ? $_POST["search"] : "";
    
    //投稿が押されたら遷移
    if(isset($_POST["submit"]))
    {
        header("Location: https://tech-base.net/tb-270313/mission6/submit.php");
        exit;
    }
    
    //検索がかけられたら名前が一致している投稿を取得
    else if (!empty($search)) 
    {
        $sql = "SELECT post.text, post.image, post.created, account.name, account.icon
                FROM post 
                JOIN account ON post.user_id = account.id 
                WHERE account.name LIKE :search 
                ORDER BY post.created DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
     }
     
     //検索がなかったら投稿が新しい順で取得
     else
     {
       $sql = "SELECT post.user_id, post.text, post.image, post.created, account.name, account.icon
               FROM post
               JOIN account ON post.user_id = account.id 
               ORDER BY post.created DESC";
       $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $results = $stmt->fetchAll();


      foreach ($results as $row) 
      {
            //style.cssの設定で投稿枠を作る
            echo "<div class='post'>";
            echo "<div class='post-header'>";
            
            //アイコンを切り抜き可で円形に出力
            if (!empty($row['icon'])) 
            {
                echo "<img src='icon/" . htmlspecialchars($row['icon']) . "' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px; object-fit: cover;'>";
                
            } 
            
            else 
            {
                echo "<img src='icons/default.png' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px;'>";
            }
            
            echo "<p><strong><a href='lookuser.php?user_id=".$row['user_id']."'style='color:inherit; text-decoration:none;'>" . htmlspecialchars($row['name']) . "</a></strong> さんの投稿</p>";
            echo "</div>";
            
            //投稿画像を角を丸くして出力
            if (!empty($row['image'])) 
            {
                echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' style='width: 520px; border-radius: 15px;' ><br>";
            }
            
            echo "<p>" . nl2br(htmlspecialchars($row['text'])) . "</p>";
            echo "<small>" . htmlspecialchars($row['created']) . "</small>";
            echo "</div>";
      }
  ?>
  
  </div>
</body>
</html>