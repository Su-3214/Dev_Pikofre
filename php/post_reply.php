<?php
session_start();

//ファイルの読み込み
require_once "db_connect.php";

// ログインチェック
if (!isset($_SESSION['u_id'])) {
  header("Location: login.php");
  exit;
}

$u_id = $_SESSION['u_id'];
$game_id = $_SESSION['game_id'] ?? 50000; // セッションがない場合のフォールバック

// 親投稿のIDを取得 (初期表示はpost_home.phpから、とどまる場合はhiddenから)
$parent_post_id = $_POST['post_id'] ?? null;

// 親投稿データ格納用
$parent_post = null;

if ($parent_post_id) {
  // 親投稿の情報を取得
  $sql_parent = "SELECT * FROM piko_post WHERE post_id = :post_id";
  $stmt_parent = $pdo->prepare($sql_parent);
  $stmt_parent->bindValue(':post_id', $parent_post_id, PDO::PARAM_INT);
  try {
    $stmt_parent->execute();
    $parent_post = $stmt_parent->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    error_log($e->getMessage());
  }
}

// 返信投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_submit'])) {
  $reply_content = $_POST['reply_text'] ?? '';
  // 親IDは再度POSTから取得 (上の$parent_post_idと同じはず)

  if ($parent_post_id && $reply_content !== '') {
    // ユーザー名取得 (post_add.phpと同様の処理)
    $stmt_user = $pdo->prepare("SELECT u_name FROM user WHERE u_id = :u_id");
    $stmt_user->bindValue(':u_id', $u_id, PDO::PARAM_INT);
    $stmt_user->execute();
    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $u_name = $user_data['u_name'] ?? 'Unknown';

    // INSERT処理
    $sql_insert = "INSERT INTO piko_post (game_id, u_id, u_name, post_detail, reply_id, post_date) 
                   VALUES (:game_id, :u_id, :u_name, :post_detail, :reply_id, NOW())";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindValue(':game_id', $game_id, PDO::PARAM_INT);
    $stmt_insert->bindValue(':u_id', $u_id, PDO::PARAM_INT);
    $stmt_insert->bindValue(':u_name', $u_name, PDO::PARAM_STR);
    $stmt_insert->bindValue(':post_detail', $reply_content, PDO::PARAM_STR);
    $stmt_insert->bindValue(':reply_id', $parent_post_id, PDO::PARAM_INT);

    try {
      $stmt_insert->execute();
      // 送信成功したら掲示板へ
      header("Location: post_home.php");
      exit;
    } catch (PDOException $e) {
      $error_message = "送信に失敗しました: " . $e->getMessage();
    }
  } else {
    $error_message = "内容が空か、投稿元が見つかりません。";
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>PikoPikoFriends - 返信</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../css/reply.css">
  <style>
    /* 左側固定メニュー */
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

    /* 募集作成ボタン (ピコる) */
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

    @media (max-width: 800px) {
      .sidebar {
        position: static;
        width: 90%;
        margin: 20px auto 20px auto;
        text-align: center;
        transform: none;
      }
    }

    .error-msg {
      color: red;
      text-align: center;
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <a href="post_add.php" class="create-btn">ピコる</a>
  </div>

  <div class="container">

    <main class="main">

      <?php if (isset($error_message)): ?>
        <p class="error-msg"><?= htmlspecialchars($error_message) ?></p>
      <?php endif; ?>

      <?php if ($parent_post): ?>
        <div class="post">
          <div class="post-text">
            <strong><?= htmlspecialchars($parent_post['u_name']) ?></strong><br>
            <?= nl2br(htmlspecialchars($parent_post['post_detail'])) ?>
          </div>
          <?php if (!empty($parent_post['post_image'])): ?>
            <img src="<?= htmlspecialchars($parent_post['post_image']) ?>" alt="投稿画像">
          <?php endif; ?>
        </div>

        <div class="reply-area">
          <form method="post" action="">
            <!-- 親投稿IDを引き継ぐ -->
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($parent_post_id) ?>">

            <textarea name="reply_text" placeholder="返信を書く…" required></textarea>
            <button class="send-btn" type="submit" name="reply_submit">返信を送信</button>
          </form>
        </div>
      <?php else: ?>
        <p style="text-align:center; margin-top:20px;">
          投稿が見つかりません。<br>
          <a href="post_home.php">掲示板に戻る</a>
        </p>
      <?php endif; ?>

    </main>

  </div>

  <script>
    // 必要であればJS追加
  </script>
  <script src="../javascript/index.js"></script>
</body>

</html>