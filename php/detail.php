<?php
//URLからinfo_idを取得
$id = $_GET['info_id'];

//DB接続
$pdo = new PDO(
    'mysql:host =  mysql325.phy.lolipop.lan; dbname = LAA1688829-pikopiko; charset = utf8mb4',
    'LAA1688829',
    'GIroku2434',
);

//SELECTで記事を1件を取得
$sql = "SELECT * FROM info WHERE info_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

//1件だけ取得
$article = $stmt->fetch(PDO::FETCH_ASSOC);

//記事が存在しない場合のチェック
if(!$article){
    echo "404 Not Found おいぃ ページが存在しとらんぞぉ";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= $article['game_name'] ?> の記事</title>
    <link rel = "stylesheet" href = "style.css">
</head>
<body>
    <h1><?= $article['game_name'] ?></h1>
    <img src = "<?= $article['info_image'] ?>" alt=""  style = "max-width; 300px;">
    <p><?= nl2br($article['info_detail']) ?></p>
    <small>更新日：<?= $article['update_date'] ?></small>

    <br><br>
    <a href="kouryakuhome.php">攻略記事一覧に戻る</a>
    
</body>
</html>