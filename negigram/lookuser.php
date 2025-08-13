<?php
session_start();
include_once "dbconnect.php";
$pdo = db();

// URLからuser_idを取得
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// 該当ユーザーの投稿を取得
$sql = "SELECT post.text, post.image, post.created, account.name, account.icon, account.message
        FROM post
        JOIN account ON post.user_id = account.id
        WHERE post.user_id = :user_id
        ORDER BY post.created DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title><?php echo htmlspecialchars($results[0]['name'] ?? 'ユーザー'); ?> さんの投稿一覧</title>
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
                <a href="timeline.php" style="color:white;">タイムラインへ</a>
                on：<?php echo htmlspecialchars($_SESSION["username"]); ?>
            </div>
        </strong>
  </div>
  <br>
  
  <div class="post-container">
     <img src='icon/<?php echo $results[0]['icon'] ?? 'default.png';?>' style='width: 60px; height: 60px; border-radius: 50%; vertical-align: middle; margin-right: 10px; object-fit:cover;'>
     <span style='; font-size:25px'><strong><?php echo htmlspecialchars($results[0]['name']); ?></strong></span>
     <p><?php echo nl2br(htmlspecialchars($results[0]['message'])); ?></p>
     <br><br><br>
     <hr>
    <h2 class="submit-btn"><div class="center"><?php echo htmlspecialchars($results[0]['name'] ?? 'ユーザー'); ?> さんの投稿一覧</div></h2>
    <?php
      foreach ($results as $row) 
      {
          echo "<div class='post'>";
          echo "<div class='post-header'>";
          
          if (!empty($row['icon'])) 
          {
              echo "<img src='icon/" . htmlspecialchars($row['icon']) . "' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px; object-fit: cover;'>";
          }
          
          else 
          {
              echo "<img src='icons/default.png' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px;'>";
          }
          
          echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong> さんの投稿</p>";
          echo "</div>";
          
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