<?php
// DB接続
require_once "db_connect.php";

// 表示するゲームID
$game_id = 50000;

// SQL
$sql = "SELECT * FROM game_info WHERE game_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$game_id]);

// 結果取得
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>攻略記事ホーム</title>
    <link rel="stylesheet" href="../css/conquest.css">
    <link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet">

    <style>
        .banner {
            width: 100%;
            height: 100px;
            background: linear-gradient(180deg, #377B98, #8A6CBE);
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        .vertical-line {
            border-left: 2px solid black;
            height: auto;
            margin: 20px;
        }
    </style>
</head>

<body>

    <div class="cards">
        <?php foreach ($articles as $a): ?>
            <a href="detail.php?info_id=<?= $a['info_id'] ?>" class="card">
                <!--↳ゲームの詳細文(説明文)-->
                <img src="<?= $a['info_image'] ?>" class="thumb">
                <!--↳ゲームのサムネイル画像、説明で欠かせない画像-->
                <div class="text">
                    <h3><?= $a['game_name'] ?></h3>
                    <p><?= $a['info_detail'] ?></p>
                    <small><?= $a['update_date'] ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>