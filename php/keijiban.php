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
</head>

<body>

  <header>
    <div class="title">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/home/home.php">PikoPikoFriends</a>
    </div>
    <div class="menu">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/announcement.php">通知</a>
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/logout.php">ログアウト</a>
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/profile.php">プ</a>
    </div>
  </header>

  <div class="container">

    <div class="sidebar-left">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/pikoru.php">ピ</a>
    </div>

    <main class="main">
      <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>

          <div class="post">
            <p><strong><?= htmlspecialchars($post['u_id']) ?></strong>
              <br><?= htmlspecialchars($post['post_detail']) ?>
            </p>
            <img src="<?= htmlspecialchars($post['post_image']) ?>" alt="投稿画像">
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

    <div class="sidebar-right">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/kouryakuhome.php">攻略記事</a>
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/recruit.php">募集</a>
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/keijiban.php">掲示板</a>
    </div>

  </div>

  <!-- 下メニュー -->
  <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/keijiban.php">掲示板</a>
  <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/keijiban_make.php">ピ</a>
  <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/reply.php">返</a>

</body>

</html>