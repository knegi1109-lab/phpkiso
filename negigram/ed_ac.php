<?php
session_start();

// ログインしてない場合はログイン画面へ
if (!isset($_SESSION["user_id"])) 
{
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];//セッションから自分のid取得

// sql接続
include_once "dbconnect.php";
$pdo = db();

$message = "";//初期メッセージ

if (isset($_POST["delete"]) && !empty($_POST["delete_id"])) 
{
    $delete_id = $_POST["delete_id"];

    // 指定した自分の投稿だけ削除
    $sql = "DELETE FROM post WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id", $delete_id, PDO::PARAM_INT);
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $message = "投稿を削除しました！";
}

// フォーム送信時の処理
if (isset($_POST["update"]) && !empty($_POST["new_name"])) 
{
    $new_name = $_POST["new_name"];
    $new_message=$_POST["stme"];

    // アイコン画像アップロード処理
    $icon_name = ""; // デフォルト空
    if (!empty($_FILES["icon"]["name"])) 
    {
        $icon_name = uniqid() . "_" . basename($_FILES["icon"]["name"]);
        move_uploaded_file($_FILES["icon"]["tmp_name"], "icon/" . $icon_name);//アイコンフォルダに保存
    }

    // SQL準備（アイコン有無で分岐）
    if (!empty($icon_name)) 
    {
        $sql = "UPDATE account SET name = :new_name, icon = :icon, message = :new_message WHERE id = :user_id";
    } 
    
    else 
    {
        $sql = "UPDATE account SET name = :new_name, message = :new_message WHERE id = :user_id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":new_name", $new_name, PDO::PARAM_STR);
    $stmt->bindValue(":new_message", $new_message, PDO::PARAM_STR);
    
    if (!empty($icon_name)) 
    {
        $stmt->bindValue(":icon", $icon_name, PDO::PARAM_STR);
    }
    
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // セッションも更新！
    $_SESSION["username"] = $new_name;
    $_SESSION["user_message"]=$new_message;
    $message = "プロフィールを更新しました！";
}

else if(isset($_POST["update"]) && !isset($_POST["new_name"]))
{
    echo "<script>alert('名前を入力してください'); </script>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>プロフィール編集</title>
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
                <a href="timeline.php" style="color:white;">←タイムラインへ</a>
                <a href="myac.php" style="color:white;">ユーザー</a>
            </div>
        </strong>
    </div>
  <p id="site-title" style="text-align:center; font-weight:bold;">プロフィール編集</p>

  <!--アカウント編集フォーム-->
  <form method="post" enctype="multipart/form-data" class="center">
    <label>新しいユーザー名：</label><br>
    <input type="text" name="new_name" value="<?php echo htmlspecialchars($_SESSION["username"]); ?>"><br><br>

    <label>新しいアイコン画像：</label><br>
    <input type="file" name="icon"><br><br>
    
    <label>新しいステータスメッセージ</label>
    <?php $stme = isset($_SESSION["user_message"]) ? $_SESSION["user_message"] : "ステメ"; ?><br>
    <textarea type="text" name="stme" value="<?php echo $stme?>" rows="5" cols="60"></textarea><br>

    <input type="submit" name="update" value="変更する">
  </form>

  <p style="color: green;"><?php echo $message; ?></p>
  <div class="center"><strong><?php echo htmlspecialchars($_SESSION["username"]); ?>の投稿一覧</strong></div>
  
<!--style.cssの設定で投稿の幅等きめる-->
<div class="post-container">
<?php
//ログインしているアカウントと投稿を連結して取り出し、投稿が新しい順に出す
$sql = "SELECT post.id, post.text, post.image, post.created, account.name, account.icon
        FROM post
        JOIN account ON post.user_id = account.id 
        WHERE post.user_id = :user_id
        ORDER BY post.created DESC";
        
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll();

foreach ($results as $row) 
  {
      //style.cssでの設定で投稿枠、投稿のヘッダーを作る
      echo "<div class='post'>";
      echo "<div class='post-header'>";
      
      if (!empty($row['icon'])) 
      {
          //切り抜きありで画像を円形に出力
          echo "<img src='icon/" . htmlspecialchars($row['icon']) . "' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px; object-fit: cover;'>";
      } 
      
      else 
      {
          //切り抜きありで画像を円形に出力
          echo "<img src='icons/default.png' style='width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; margin-right: 10px;'>";
      }
      
      echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong> さんの投稿</p>";
      echo "</div>";
            
      //投稿された画像を角を丸くして出力
      if (!empty($row['image'])) 
      {
          echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' style='width: 520px; border-radius: 15px;' ><br>";
      }
      
      //改行文字をちゃんと変換して出力
      echo "<p>" . nl2br(htmlspecialchars($row['text'])) . "</p>";
      echo "<small>" . htmlspecialchars($row['created']) . "</small>";
      
      //投稿の下に削除フォーム
      echo "<form method='post' style='margin-top:10px;'>
      　　　　<input type='hidden' name='delete_id' value=". $row['id'] . ">
            <input type='submit' name='delete' value='削除する' class='delete-btn'>
          　</form>";
      echo "</div>";
  }
?>
</div>
</body>
</html>