<?php
//ファイルの読み込み
require_once "db_connect.php";
//募集データの取得処理
$sql_recruit = "SELECT * FROM game_recruitment "; 
$stmt_recruit = $pdo->prepare($sql_recruit);
try{
    $stmt_recruit->execute();
}catch(PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}
$recruit = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);
//POSTされたデータからINSERT処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募集作成画面</title>
</head>
<body>
    
</body>
</html>