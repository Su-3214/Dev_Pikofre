<?php
// DB接続
$pdo = new PDO(
    'mysql:host=mysql325.phy.lolipop.jp;dbname=LAA1688829-pikopiko;charset=utf8mb4',
    'LAA1688829',
    'GIroku2434'
);

// 表示するゲームID
$game_id = 1;

// SQL
$sql = "SELECT * FROM info WHERE game_id = ?";
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
    <link rel="stylesheet" href="kouryakuhome.css">
    <link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet">

    <style>
        .banner{
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
         <div class="banner">
        <img src="/images/画像1.png" alt="画像1" style="position: absolute; left: 20px; top: 20px; width: 80px; height: auto;">
        <div style="position: absolute; left: 115px; top: 45px; font-size: 30px; color: aqua;">
            PikoPikoFriends
        </div>
        <i class="fas fa-bell fa-2x" style="position: relative; left: 1150px; top: 20px; color: white;"></i>
        <div style="position: relative; left: 1200px; top: 20px; font-size: 25px; color: red;">
            ログアウト
        </div>
        <i class="fas fa-circle fa-2x" style="position: relative; left: 1250px; top: 20px; color: white;"></i>
    </div>

    <div class = "cards">
        <?php foreach ($articles as $a): ?>
            <a href="detail.php?info_id=<?= $a['info_id'] ?>" class="card">
                                                <!--↳ゲームの詳細文(説明文)-->
            <img src ="<?=$a['info_image'] ?>" class = "thumb">
                            <!--↳ゲームのサムネイル画像、説明で欠かせない画像-->
                <div class = "text">
                    <h3><?= $a['game_name'] ?></h3>
                    <p><?= $a['info_detail'] ?></p>
                    <small><?= $a['update_date'] ?></small>
                </div>
        </a>
    <?php endforeach; ?>
    </div>
</body>
</html>