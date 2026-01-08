<?php
//ファイルの読み込み
require_once "db_connect.php";

//セッションにてゲームIDの情報取得
$_SESSION['game_id'] = 50000;
$game_id = $_SESSION['game_id'];

//募集テーブルの情報取得
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = :game_id ORDER BY recruit_start";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->bindParam(':game_id', $game_id, PDO::PARAM_INT);

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

    <style>
        /* 左側固定メニュー（通常は左に固定） */
        .sidebar {
            position: fixed;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        /* 募集作成ボタン */
        .create-btn {
            display: inline-block;
            background: #ff7b00;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1em;
        }

        .create-btn:hover {
            opacity: 0.85;
        }

        /* 画面幅が800px以下のとき、重ならないように上へ移動させる */
        @media (max-width: 800px) {
            .sidebar {
                position: static;
                /* 固定解除 */
                width: 90%;
                margin: 0 auto 20px auto;
                text-align: center;
            }
        }


        body {
            background: #f0f0f0;
            font-family: "Helvetica", "Arial", sans-serif;
            margin: 0;
            padding: 0;
            /* margin, flex, align-items removed to allow full width header/footer */
        }

        .content-wrapper {
            margin: 20px;

            /* ★カード中央揃えのため追加★ */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card {
            background: #e2e2e2;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

            width: 90%;
            max-width: 600px;
            /* 中央揃えで画面幅に依存しすぎないため */
        }

        .header-name {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .detail-item {
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 0.95em;
        }

        .join-btn {
            background: #ff30c8;
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 1em;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        .join-btn:hover {
            opacity: 0.85;
        }
    </style>

</head>

<body>
    <div class="content-wrapper">
        <div class="sidebar">
            <a href="recruit_add.php" class="create-btn">募集作成</a>
        </div>

        <?php if (count($recruits) > 0): ?>
            <?php foreach ($recruits as $recruit): ?>

                <div class="card">

                    <div class="header-name">
                        <?= htmlspecialchars($recruit['u_name']) ?>
                    </div>

                    <div class="detail-item">
                        募集人数：<?= htmlspecialchars($recruit['recruit_number']) ?>
                    </div>

                    <div class="detail-item">
                        ボイスチャット：<?= htmlspecialchars($recruit['recruit_vc']) ?>
                    </div>

                    <div class="detail-item">
                        募集詳細：<br>
                        <?= nl2br(htmlspecialchars($recruit['recruit_detail'])) ?>
                    </div>

                    <form action="recruit_room_number.php" method="post">
                        <input type="hidden" name="recruit_id" value="<?= $recruit['recruit_id'] ?>">
                        <input type="submit" value="参加" class="join-btn">
                    </form>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>現在募集はありません。</p>
        <?php endif; ?>
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>