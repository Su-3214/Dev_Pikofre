<?php
session_start();

//ファイルの読み込み
require_once "db_connect.php";

// ログインチェック（例）
if (!isset($_SESSION['u_id'])) {
  header("Location: login.php");
  exit;
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>PikoPikoFriends - 返信</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../css/reply.css">
</head>

<body>
  <header>
    <div class="title">
      <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/index.php">PikoPikoFriends</a>
    </div>
    <div class="menu">
      <a href="announcement.php">通知</a>
      <a href="php/login.php">ログアウト</a>
      <a href="profile.php">プ</a>
    </div>
  </header>

  <div class="container">
    <div class="sidebar-left">
      <a href="pikoru.php">ピ</a>
    </div>

    <main class="main">

      <div class="post">
        <div class="post-text">
          こいつ強化されたのに結局スパローかよ
          #シア
        </div>
        <img src="https://via.placeholder.com/400x200?text=シアに強化を..." alt="">
      </div>

      <div class="reply-area">
        <form method="post" action="reply_send.php">
          <textarea name="reply_text" placeholder="返信を書く…" required></textarea>
          <button class="send-btn" type="submit">返信を送信</button>
        </form>
      </div>

    </main>

    <div class="sidebar-right">
      <a href="guid.php">攻略記事</a>
      <a href="Recruit.php">募集</a>
      <a href="keiji.php">掲示板</a>
    </div>
  </div>

  <script>
    function sendReply() {
      alert("返信を送信しました（ベータ版）\n掲示板に戻ります");
      location.href = "keiji.php";
      return false;
    }
  </script>

  <a href="http://localhost/keijiban.html/keijiban.php">掲示板</a>
  <a href="http://localhost/keijiban.html/keijiban_make.php">ピ</a>
  <a href="http://localhost/keijiban.html/reply.php">返</a>


</body>

</html>