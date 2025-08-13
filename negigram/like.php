<?php
session_start();
header('Content-Type: application/json');

include_once "dbconnect.php";
$pdo = db();

if (!isset($_SESSION["user_id"]) || empty($_POST["post_id"])) {
    echo json_encode(["success" => false]);
    exit;
}

$user_id = $_SESSION["user_id"];
$post_id = intval($_POST["post_id"]);

// すでにいいねしているか確認
$check_sql = "SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id";
$check_stmt = $pdo->prepare($check_sql);
$check_stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
$check_stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$check_stmt->execute();

if (!$check_stmt->fetch()) {
    $insert_sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
    $insert_stmt = $pdo->prepare($insert_sql);
    $insert_stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
    $insert_stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $insert_stmt->execute();
}

// 最新のいいね数を取得
$count_sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
$count_stmt->execute();
$like_count = $count_stmt->fetchColumn();

echo json_encode(["success" => true, "like_count" => $like_count]);
?>