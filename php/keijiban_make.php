<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>PikoPikoFriends - 投稿作成</title>
  <link rel="stylesheet" href="keijiban_make.css">
</head>
<body>

<header>
  <div class="title">
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/index.html">PikoPikoFriends</a>
  </div>

  <div class="menu">
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/announcement.php">通知</a>
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/php/login.php">ログアウト</a>
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/profile.php">プ</a>
  </div>
</header>

<div class="container">

  <!-- 左メニュー -->
  <div class="sidebar-left">
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/pikoru.php">ピ</a>
  </div>

  <!-- メインフォーム -->
  <main>
    <div class="form-box">
      <h2>掲示板に投稿</h2>

      <form action="keijiban.html" method="get">
        <textarea name="content" placeholder="メッセージを入力..."></textarea>

        <div class="upload-section">
          <label>画像をアップロード：</label>
          <input type="file" name="image">
        </div>

        <button type="submit" class="submit-btn">投稿する</button>
      </form>

    </div>
  </main>

  <!-- 右側メニュー -->
  <div class="sidebar-right">
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/guid.php">攻略記事</a>
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/Recruit.php">募集</a>
    <a href="https://chlorine3214.bitter.jp/Dev_Chlorine/keiji.php">掲示板</a>
  </div>

</div>
<!-- 下メニュー -->
<a href="./keijiban.html">掲示板</a>
<a href="./keijiban_make.html">ピ</a>
<a href="reply.html">返</a>


</body>
</html>
