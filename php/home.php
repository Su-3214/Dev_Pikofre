<?php
session_start();
require_once "db_connect.php";

$_SESSION['u_id'] = 1;
$_SESSION['game_id'] = 50000;

//攻略記事を取得
$sql_info = "SELECT * FROM game_info ORDER BY update_date LIMIT 3";
$stmt_info = $pdo->prepare($sql_info);

try {
    $stmt_info->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

$infos = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

//募集を取得
$sql_recruit = "SELECT * FROM game_recruitment ORDER BY recruit_start LIMIT 3";
$stmt_recruit = $pdo->prepare($sql_recruit);

try {
    $stmt_recruit->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

//掲示板を取得
$sql_post = "SELECT * FROM piko_post ORDER BY post_date LIMIT 3";
$stmt_post = $pdo->prepare($sql_post);

try {
    $stmt_post->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>

    <style>
        body {
            background: #f5f5f5;
            margin: 0;
            font-family: 'Yu Gothic', sans-serif;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
        }

        /* 最新記事 */
        .section-title {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .article-card {
            background: white;
            border: 2px solid black;
            padding: 15px;
        }

        .article-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .article-thumb {
            width: 110px;
            height: 70px;
            background: #000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 13px;
        }

        .article-text {
            font-size: 14px;
            line-height: 1.4;
        }

        /* 募集 */
        .recruit-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
        }

        .recruit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recruit-title {
            font-size: 22px;
            font-weight: bold;
        }

        .recruit-game-icon {
            width: 50px;
            height: 50px;
            background: gray;
            border-radius: 10px;
        }

        .recruit-body {
            margin-top: 10px;
            background: #000;
            color: white;
            padding: 10px;
            border-radius: 10px;
        }

        .join-btn {
            display: block;
            width: 100%;
            margin-top: 15px;
            background: #e100ff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        /* 掲示板 */
        .post-card {
            background: white;
            margin-top: 30px;
            padding: 15px;
            border-radius: 20px;
        }

        .post-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .post-item:last-child {
            border-bottom: none;
        }

        .post-thumb {
            width: 90px;
            height: 80px;
            background: #000;
            border-radius: 10px;
            margin-right: 15px;
        }

        .post-text {
            font-size: 14px;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- 最新記事 -->
    <h2 class="section-title">最新の記事</h2>
    <div class="article-card">

        <?php foreach ($infos as $info): ?>
            <div class="article-item">
                <div class="article-thumb">サムネ</div>
                <div class="article-text">
                    <?= htmlspecialchars($info['info_detail']) ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <!-- 募集 -->
    <?php foreach ($recruits as $recruit): ?>
        <div class="recruit-card">
            <div class="recruit-header">
                <div>
                    <div class="recruit-title"><?= htmlspecialchars($recruit['u_name']) ?></div>
                    <div style="font-size:12px; color:#777;">残り<?= htmlspecialchars($recruit['recruit_number']) ?>人</div>
                </div>
                <div class="recruit-game-icon"></div>
            </div>

            <div class="recruit-body">
                <?= nl2br(htmlspecialchars($recruit['recruit_detail'])) ?>
            </div>

            <a class="join-btn" href="https://2301037.perma.jp/piko/php/room_number.php">参加</a>
        </div>
    <?php endforeach; ?>

    <!-- 掲示板 -->
    <div class="post-card">

        <?php foreach ($posts as $post): ?>
            <div class="post-item">
                <div class="post-thumb"></div>
                <div class="post-text">
                    <?= htmlspecialchars($post['post_detail']) ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
