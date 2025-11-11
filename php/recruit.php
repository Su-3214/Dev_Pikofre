<?php
//ファイルの読み込み
require_once "db_connect.php";

//セッションにてゲームIDの情報取得
$game_id = $_SESSION['game_id'];


//募集テーブルの情報を特定のゲームIDをもとに昇順で取得
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = :game_id ORDER BY recruit_start"; 
$stmt_recruit = $pdo->prepare($sql_recruit);
//パラメータバインディングでセキュリティ強化
$stmt_recruit->bindParam(':game_id', $game_id, PDO::PARAM_INT);

//sql実行処理
try{
    $stmt_recruit->execute();
}catch(PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

//sql実行で得た情報の取得処理
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
                <?php 
                    //現在テーブルに[u_name]がないためにコメントアウト中
                    //echo htmlspecialchars($recruit['u_name']); ?>
                <span><?php echo htmlspecialchars($recruit['recruit_number']); ?></span>
                <span><?php echo htmlspecialchars($recruit['recruit_vc']); ?></span>
                <span><?php echo htmlspecialchars($recruit['recruit_detail']); ?>  </span>
                <form action="room_number.php" method="post">
                    <input type="hidden" name="recruit_id" value="<?php echo $recruit['rectuir_id'] ?>">
                    <input type="submit" value="参加">
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>まだレビューがありません。</p>
        <?php endif; ?>
</body>
</html>