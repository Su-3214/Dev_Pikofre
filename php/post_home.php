<?php
//ファイルの読み込み
require_once "db_connect.php";

//セッションにてゲームIDの情報取得
$_SESSION['game_id'] = 50000;
$game_id = $_SESSION['game_id'];

//投稿テーブルの情報取得
$sql_post = "SELECT * FROM piko_post WHERE game_id = :game_id ORDER BY post_date";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->bindParam(':game_id', $game_id, PDO::PARAM_INT);

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
  <title>PikoPikoFriends - 掲示板</title>
  <link rel="stylesheet" href="../css/keijiban.css">
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

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 90%;
        margin: 20px auto 20px auto;
        text-align: center;
        transform: none;
      }
    }

    /* 返信ボタン */
    .reply-btn {
      background: #5ecfff;
      border: none;
      color: black;
      padding: 8px 16px;
      font-size: 0.9em;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
    }

    .reply-btn:hover {
      opacity: 0.85;
    }

    /* 返信表示エリア */
    .reply-container {
      margin-top: 10px;
      margin-left: 20px;
      border-left: 3px solid #ddd;
      padding-left: 10px;
    }

    .reply-post {
      background: #f0f8ff;
      /* 少し薄い色または違う色 */
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <a href="post_add.php" class="create-btn">ピコる</a>
  </div>

  <!--
  <header>
    <div class="title">
      <a href="./home.php">PikoPikoFriends</a>
    </div>
    -->

  <div class="container">

    <main class="main">
      <?php
      // 投稿を親と子（返信）に分ける
      $parents = [];
      $replies = [];

      foreach ($posts as $post) {
        if ($post['reply_id'] == 0 || is_null($post['reply_id'])) {
          $parents[] = $post;
        } else {
          $replies[$post['reply_id']][] = $post;
        }
      }
      ?>

      <?php if (count($parents) > 0): ?>
        <?php foreach ($parents as $parent): ?>
          <div class="post">
            <p><strong><?= htmlspecialchars($parent['u_name']) ?></strong>
              <br><?= nl2br(htmlspecialchars($parent['post_detail'])) ?>
            </p>
            <?php if (!empty($parent['post_image'])): ?>
              <img src="<?= htmlspecialchars($parent['post_image']) ?>" alt="投稿画像">
            <?php endif; ?>

            <form action="post_reply.php" method="post" style="text-align: right;">
              <input type="hidden" name="post_id" value="<?= $parent['post_id'] ?>">
              <input type="submit" value="返信" class="reply-btn">
            </form>

            <!-- 返信があれば表示 -->
            <?php if (isset($replies[$parent['post_id']])): ?>
              <div class="reply-container">
                <?php foreach ($replies[$parent['post_id']] as $reply): ?>
                  <div class="reply-post">
                    <p><strong><?= htmlspecialchars($reply['u_name']) ?></strong>
                      <br><?= nl2br(htmlspecialchars($reply['post_detail'])) ?>
                    </p>
                    <?php if (!empty($reply['post_image'])): ?>
                      <img src="<?= htmlspecialchars($reply['post_image']) ?>" alt="返信画像" style="width:100%; border-radius:10px; margin-top:5px;">
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center; margin-top:20px;">現在投稿はありません。</p>
      <?php endif; ?>

    </main>

    <!--
    <div class="sidebar-right">
      <a href="./conquest.php">攻略記事</a>
      <a href="./recruit_home.php">募集</a>
      <a href="./post_home.php">掲示板</a>
    </div>
    -->

  </div>

  <!-- 下メニュー -->
  <!--
  <a href="./post_home.php">掲示板</a>
  <a href="./post_add.php">ピ</a>
  <a href="./post_reply.php">返</a>
  -->
  <script src="../javascript/index.js"></script>
</body>

</html>