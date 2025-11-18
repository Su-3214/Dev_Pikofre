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
        body {
            font-family: sans-serif;
            background-color: #fff;
            margin: 0;

            /* 中央揃え */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* ★画面の 65% の幅に調整★ */
        .wrapper {
            text-align: center;
            width: 65%;            /* ← 横幅 6〜7 割に相当 */
            max-width: 800px;      /* PC で広がりすぎないように制限 */
            min-width: 300px;      /* スマホ時の最低幅 */
        }

        .label {
            font-size: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 15px 20px;
            width: 100%;           /* wrapper に合わせて広がる */
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
            width: 100%;           /* 横幅を統一 */
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

</body>
</html>
