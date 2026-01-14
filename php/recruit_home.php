<?php
//セッション開始
session_start();

//ファイルの読み込み
require_once "db_connect.php";

//セッションにてゲームIDの情報取得
$_SESSION['game_id'] = 50000;
$game_id = $_SESSION['game_id'];

// 自分のユーザーIDを取得 (未ログイン時は 0 等にしておく)
$my_u_id = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : 0;

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recruit_id'])) {
    // 自分の募集かつ、指定のIDのものを削除
    $del_id = $_POST['delete_recruit_id'];
    $sql_del = "DELETE FROM game_recruitment WHERE recruit_id = :recruit_id AND u_id = :u_id";
    $stmt_del = $pdo->prepare($sql_del);
    $stmt_del->bindParam(':recruit_id', $del_id, PDO::PARAM_INT);
    $stmt_del->bindParam(':u_id', $my_u_id, PDO::PARAM_INT);

    try {
        $stmt_del->execute();
        // 再読み込みして二重送信防止
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "削除に失敗しました。";
    }
}

//募集テーブルの情報取得
// 自分の募集 (u_id = :my_u_id) を優先的に表示し、それ以外は recruit_start 順
// MySQLでは (condition) は true=1, false=0 なので DESC で true が先頭に来る
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = :game_id ORDER BY (u_id = :my_id) DESC, recruit_start";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->bindParam(':game_id', $game_id, PDO::PARAM_INT);
$stmt_recruit->bindParam(':my_id', $my_u_id, PDO::PARAM_INT);

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

        /* 削除ボタンのスタイル */
        .delete-btn {
            background: #ff4b4b;
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 1em;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            margin-left: 10px;
            /* 参加ボタンとの間隔 */
        }

        .delete-btn:hover {
            opacity: 0.85;
        }

        /* 区切り線のスタイル */
        .recruit-separator {
            width: 90%;
            max-width: 600px;
            border: 0;
            border-top: 2px dashed #999;
            margin: 10px 0 40px 0;
            /* 上下の余白調整 */
        }
    </style>

</head>

<body>
    <div class="content-wrapper">
        <div class="sidebar">
            <a href="recruit_add.php" class="create-btn">募集作成</a>
        </div>

        <?php
        $own_recruit_shown = false; // 自分の募集を表示したかどうかのフラグ
        $separator_shown = false;   // 区切り線を表示したかどうかのフラグ
        ?>

        <?php if (count($recruits) > 0): ?>
            <?php foreach ($recruits as $recruit): ?>

                <?php
                // 自分の募集と他の募集の切り替わり判定
                $is_my_recruit = ($recruit['u_id'] == $my_u_id);

                // もし「自分の募集が表示済み」かつ「今回のループが自分の募集ではない」かつ「まだ区切り線が出ていない」なら線を表示
                // ※ もし自分の募集が1つもなければこの線は出ないことになるので、
                //    自分の募集が0個で他人の募集がある場合については要件次第だが、
                //    「自分の募集と他人の募集の間」という要望なので、両方存在する場合のみ、あるいは
                //    上部に自分の募集エリアがあることを示す形にするか。
                //    ここではシンプルに「自分の募集ブロックが終わった直後」に出すロジックにする。

                if ($own_recruit_shown && !$is_my_recruit && !$separator_shown) {
                    echo '<hr class="recruit-separator">';
                    $separator_shown = true;
                }

                if ($is_my_recruit) {
                    $own_recruit_shown = true;
                }
                ?>

                <div class="card">

                    <div class="header-name">
                        <?= htmlspecialchars($recruit['u_name']) ?>
                        <!-- 自分の募集かどうかで表示を分けるなどあればここで判定も可能 -->
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

                    <div style="display:flex;">
                        <form action="recruit_room_number.php" method="post">
                            <input type="hidden" name="recruit_id" value="<?= $recruit['recruit_id'] ?>">
                            <input type="submit" value="参加" class="join-btn">
                        </form>

                        <?php if ($recruit['u_id'] == $my_u_id): ?>
                            <!-- 削除ボタン：自分の募集の場合のみ表示 -->
                            <form action="" method="post" onsubmit="return confirm('この募集を削除してもよろしいですか？');">
                                <input type="hidden" name="delete_recruit_id" value="<?= $recruit['recruit_id'] ?>">
                                <input type="submit" value="削除" class="delete-btn">
                            </form>
                        <?php endif; ?>
                    </div>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>現在募集はありません。</p>
        <?php endif; ?>
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>