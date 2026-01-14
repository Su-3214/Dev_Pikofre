<?php
//セッションスタート
session_start();
//ファイルの読み込み
require_once "db_connect.php";

//テスト用にセッションに値を追加
$_SESSION['game_id'] = 50000;



//セッションでユーザーidとゲームidを取得
$u_id = $_SESSION['u_id'];
$game_id = $_SESSION['game_id'];

//ユーザーIDからユーザーネームを取得
$sql_addrecruit = "SELECT u_name FROM user WHERE u_id = :u_id ";
$stmt_addrecruit = $pdo->prepare($sql_addrecruit);
$stmt_addrecruit->bindParam(':u_id', $u_id, PDO::PARAM_INT);

//sql実行処理
try {
    $stmt_addrecruit->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
}

//sql実行で得た情報の取得処理
$recruit_user = $stmt_addrecruit->fetch(PDO::FETCH_ASSOC);
$u_name = $recruit_user['u_name'];


//POSTされたデータからINSERT処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruit_vc = $_POST['recruit_vc'] ?? null;
    $recruit_number = $_POST['recruit_number'] ?? null;
    $recruit_detail = $_POST['recruit_detail'] ?? null;

    //インサート用のsql文
    if ($game_id && $u_id && $recruit_vc && $recruit_number) {
        $sql = "INSERT INTO game_recruitment 
                (game_id, u_id, u_name,recruit_vc, recruit_number, recruit_detail, room_number)
                VALUES (:game_id, :u_id, :u_name,:recruit_vc, :recruit_number, :recruit_detail, :room_number)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
        $stmt->bindParam(':u_name', $u_name, PDO::PARAM_STR);
        $stmt->bindParam(':recruit_vc', $recruit_vc, PDO::PARAM_STR);
        $stmt->bindParam(':recruit_number', $recruit_number, PDO::PARAM_INT);
        $stmt->bindParam(':recruit_detail', $recruit_detail, PDO::PARAM_STR);
        $stmt->bindValue(':room_number', 10, PDO::PARAM_INT);

        try {
            $stmt->execute();
            echo "<script>alert('募集を作成しました！');</script>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>登録に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>必須項目が未入力です。</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募集作成画面</title>

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
            background: #f0f0f0;
            font-family: "Helvetica", "Arial", sans-serif;
            padding: 0;
            margin: 0;
        }

        .form-wrapper {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }

        form {
            width: 100%;
        }

        /* 各項目の黒丸枠 */
        .field-box {
            background: #000;
            color: white;
            padding: 10px 18px;
            border-radius: 25px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ラベル（左側） */
        .field-title {
            font-size: 1.0em;
            font-weight: bold;
            color: white;
        }

        /* セレクト装飾 */
        select {
            background: #5ecfff;
            border: none;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
            cursor: pointer;
        }

        /* textarea外側の黒枠 */
        .textarea-wrapper {
            background: #000;
            padding: 20px;
            border-radius: 25px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* テキスト入力エリア */
        textarea {
            width: 100%;
            min-height: 160px;
            border-radius: 15px;
            border: none;
            padding: 15px;
            font-size: 1em;
            outline: none;
            resize: vertical;
            box-sizing: border-box;
        }

        /* 作成するボタン */
        button {
            background: #5ecfff;
            color: black;
            border: none;
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 1.0em;
            cursor: pointer;
            display: block;
            margin-left: auto;
        }

        button:hover {
            opacity: 0.85;
        }
    </style>

</head>

<body>
    <div class="sidebar">
        <a href="recruit_home.php" class="sidebar-btn">募集ホームへ</a>
    </div>


    <div class="form-wrapper">
        <form action="" method="POST">

            <!-- 🎮 遊ぶ人数 -->
            <div class="field-box">
                <span class="field-title">遊ぶ人数</span>
                <select name="recruit_number" required>
                    <option value="2">二人募集</option>
                    <option value="3">三人募集</option>
                    <option value="4">四人募集</option>
                    <option value="5">五人募集</option>
                </select>
            </div>

            <!-- 🎤 ボイスチャット -->
            <div class="field-box">
                <span class="field-title">ボイスチャット</span>
                <select name="recruit_vc" required>
                    <option value="必須">必須</option>
                    <option value="どちらでも">どちらでも</option>
                    <option value="なし">なし</option>
                </select>
            </div>

            <!-- 📝 募集詳細の黒丸枠 -->
            <div class="textarea-wrapper">
                <textarea name="recruit_detail" placeholder="募集の詳細を入力してください..." required></textarea>
            </div>

            <input type="hidden" name="u_name" value="<?php echo htmlspecialchars($u_name, ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit">作成する</button>

        </form>
    </div>

    <script src="../javascript/index.js"></script>
</body>

</html>