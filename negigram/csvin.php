<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>団体アカウント登録</title>
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
                <a href="make_ac.php" style="color:white;">新規登録</a>
            </div>
        </srrong>
    </div>
    
    
    <div class="center" id="site-title" style="font-size:20px; text-align:center; font-weight:bold;">
        団体登録
    </div>
    
    <!--団体登録フォーム-->
  　<form action="" method="post" enctype="multipart/form-data">
    　　<div class="center">
      　　　　<input type="file" name="csvfile" accept=".csv">
      　　　　<br><br>
      　　　　<input type="submit" name="submit" value="CSVから登録">
    　　</div>
  　</form>

<?php
//sql接続
session_start();
include_once "dbconnect.php";
$pdo = db();

if (isset($_POST["submit"]) && isset($_FILES["csvfile"])) 
{
    //ファイルのパスを読み込み
    $file = $_FILES["csvfile"]["tmp_name"];
    
    if (($handle = fopen($file, "r")) !== false) 
    {
        //ファイルの中身が終わるまでsqlのテーブルに挿入
        while (($data = fgetcsv($handle)) !== false) 
        {
            $name = $data[0];
            $pass = $data[1];
            
            //入力された名前と同じアカウントを数える
            $sql = "SELECT COUNT(*) FROM account WHERE name = :name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            //重複してたらそのアカウントを表示して飛ばす
            if($count>0)
            {
                echo $name.$pass."このアカウントが重複として認識されませんでした<br />";
            }
            
            //重複アカウントがないとき
            else
            {
                if (!empty($name) && !empty($pass)) 
                {
                    $sql = "INSERT INTO account (name, pass) VALUES (:name, :pass)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
        
        fclose($handle);
        echo "<p>登録が完了しました！</p>";
    } 
    
    else 
    {
        echo "<p>CSVファイルの読み込みに失敗しました。</p>";
    }
}
?>
</body>
</html>