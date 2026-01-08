<?php
//DB接続ファイルの読み込み
require_once "db_connect.php";

//セッションスタート
session_start();

//募集IDの受け渡し
$recruit_id = $_POST['recruit_id'];

//テスト用に部屋番号を直接代入
$room_number = 10;

//セッションにてゲームIDの情報取得
$_SESSION['game_id'] = 50000;
$game_id = $_SESSION['game_id'];

//リクルートIDをもとに募集テーブルから情報を取得
$sql_recruit = "SELECT * FROM game_recruitment WHERE recruit_id = :recruit_id";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->bindParam(':recruit_id', $recruit_id, PDO::PARAM_INT);

try {
    $stmt_recruit->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

$recruits = $stmt_recruit->fetch(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discord部屋番号</title>

    <style>
        /* 左側固定のサイドメニュー */
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

        /* ボタンのデザイン */
        .sidebar-btn {
            display: block;
            background: #ff7b00;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1em;
            margin-bottom: 10px;
            text-align: center;
        }

        .sidebar-btn:hover {
            opacity: 0.85;
        }

        /* 画面幅が800px以下のときは重ならないように上へ移動 */
        @media (max-width: 800px) {
            .sidebar {
                position: static;
                width: 90%;
                margin: 0 auto 20px auto;
                text-align: center;
            }
        }

        body {
            font-family: sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            /* margin:0, padding:0 is good for full width header/footer */
        }

        /* ★画面の 65% の幅に調整★ */
        /* ★画面の 65% の幅に調整★ */
        .wrapper {
            text-align: center;
            width: 65%;
            /* ← 横幅 6〜7 割に相当 */
            max-width: 800px;
            /* PC で広がりすぎないように制限 */
            min-width: 300px;
            /* スマホ時の最低幅 */
            margin: 50px auto;
            /* 上下に余白、左右中央揃え */
        }

        .label {
            font-size: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 15px 20px;
            width: 100%;
            /* wrapper に合わせて広がる */
            margin: 0 auto 25px;
            border-radius: 8px;
            font-size: 18px;
            background-color: #fafafa;
            box-sizing: border-box;
        }

        .black-box {
            background-color: #000;
            color: #fff;
            padding: 25px 25px;
            border-radius: 15px;
            width: 100%;
            /* 横幅を統一 */
            margin: 35px auto 30px;
            font-size: 18px;
            box-sizing: border-box;
            text-align: left;
        }

        .black-box-title {
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .invite-link {
            color: #0b6abf;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .invite-link:hover {
            text-decoration: underline;
        }

        /* スマホ用に幅を100%に近づける */
        @media (max-width: 600px) {
            .wrapper {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="recruit_home.php" class="sidebar-btn">募集ホームへ</a>
        <a href="recruit_add.php" class="sidebar-btn">募集作成</a>
    </div>


    <div class="wrapper">

        <div class="label">Discord部屋番号</div>
        <div class="info-box">
            <?= htmlspecialchars($room_number) ?>
        </div>

        <div class="label">ホストユーザー</div>
        <div class="info-box">
            <?= htmlspecialchars($recruits['u_name']) ?>
        </div>

        <div class="black-box">
            <div class="black-box-title">募集内容</div>
            <?= nl2br(htmlspecialchars($recruits['recruit_detail'])) ?>
        </div>

        <a class="invite-link" href="https://discord.gg/RHPrCnasT8" target="_blank">
            Discordサーバーに未参加の方はこちら
        </a>

    </div>

    <script src="../javascript/index.js"></script>
</body>

</html>