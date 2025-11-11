<?php
//ファイルの読み込み
require_once "db_connect.php";
//募集データの取得処理
$sql_recruit = "SELECT * FROM game_recruitment ";
$stmt_recruit = $pdo->prepare($sql_recruit);
try {
    $stmt_recruit->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム募集ベータ版</title>
</head>


<body>
    <?php if (count($recruits) > 0): ?>
        <?php foreach ($recruits as $recruit): ?>
            <?php echo htmlspecialchars($recruit['u_name']); ?>
            <?php ?>


        <?php endforeach; ?>
    <?php else: ?>
        <p>まだレビューがありません。</p>
    <?php endif; ?>
</body>

</html>