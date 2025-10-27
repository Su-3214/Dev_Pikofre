<?php
//ファイルの読み込み
require_once "db_connect.php";

$sql_recruit = "SELECT * FROM game_recruitment "; 
$stmt_recruit = $pdo->prepare($sql_recruit);
try{
    $stmt_recruit->execute();
}catch(PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}


?>




<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム募集ベータ版</title>
</head>
<body>
    
</body>
</html>