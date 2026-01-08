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

    @media (max-width: 800px) {
      .sidebar {
        position: static;
        width: 90%;
        margin: 0 auto 20px auto;
        text-align: center;
      }
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
    <div class="menu">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/announcement.php">通知</a>
      <a href="./logout.php">ログアウト</a>
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/profile.php">プ</a>
    </div>
  </header>
  -->

  <div class="container">

    <!--
    <div class="sidebar-left">
      <a href="./post_add.php">ピ</a>
    </div>
    -->

    <main class="main">
      <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>

          <div class="post">
            <p><strong><?= htmlspecialchars($post['u_name']) ?></strong>
              <br><?= htmlspecialchars($post['post_detail']) ?>
            </p>
            <img src="<?php echo htmlspecialchars($post['post_image']); ?>" alt="投稿画像">
          </div>

          <!--リプライ機能はすぐは無理なので一旦コメントアウト
          <div class="reaction-bar">
            <button>二年間待ったのに(´；ω；｀)ｳｯｳｯ</button>
          </div>
          -->
        <?php endforeach; ?>
      <?php else: ?>
        <p>現在投稿はありません。</p>
      <?php endif; ?>

    </main>

    <!--
    <div class="sidebar-right">
      <a href="./kouryakuhome.php">攻略記事</a>
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