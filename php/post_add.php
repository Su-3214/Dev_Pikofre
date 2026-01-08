<?php
//セッションスタート
session_start();
//ファイルの読み込み
require_once "db_connect.php";

// ログインチェック（例）
if (!isset($_SESSION['u_id'])) {
  header("Location: login.php");
  exit;
}

//テスト用にセッションに値を追加
$_SESSION['game_id'] = 50000;


//セッションでユーザーidを取得
$u_id = $_SESSION['u_id'];
$game_id = $_SESSION['game_id'];

//ユーザーIDからユーザーネームを取得
$sql_addpost = "SELECT u_name FROM user WHERE u_id = :u_id ";
$stmt_addpost = $pdo->prepare($sql_addpost);
$stmt_addpost->bindParam(':u_id', $u_id, PDO::PARAM_INT);

//sql実行処理
try {
  $stmt_addpost->execute();
} catch (PDOException $e) {
  error_log($e->getMessage());
  echo "データベースエラーが発生しました。";
  exit;
}

//sql実行で得た情報の取得処理
$post_user = $stmt_addpost->fetch(PDO::FETCH_ASSOC);
$u_name = $post_user['u_name'];

//POSTされたデータからINSERT処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $post_detail = $_POST['content'] ?? null;
  $reply_id = $_POST['reply'] ?? null;

  // 画像アップロード処理
  $post_image = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $upload_dir = '../images/';
    $post_image = $upload_dir . $file_name;

    if (!move_uploaded_file($file_tmp, $post_image)) {
      $error_message = "画像のアップロードに失敗しました。";
    }
  }


  //インサート用のsql文
  if ($game_id && $u_id && $post_detail && $u_name) {
    $sql = "INSERT INTO piko_post 
                (game_id, u_id, u_name, post_detail, post_image, reply_id)
                VALUES (:game_id, :u_id, :u_name, :post_detail, :post_image, :reply_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
    $stmt->bindParam(':post_detail', $post_detail, PDO::PARAM_STR);
    $stmt->bindParam(':reply_id', $reply_id, PDO::PARAM_INT);
    $stmt->bindParam(':post_image', $post_image, PDO::PARAM_STR);
    $stmt->bindParam(':u_name', $u_name, PDO::PARAM_STR);

    try {
      $stmt->execute();
      header('Location: ./post_home.php');
    } catch (PDOException $e) {
      echo "<p style='color:red;'>投稿に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
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
  <title>PikoPikoFriends - 投稿作成</title>
  <link rel="stylesheet" href="../css/keijiban_make.css">
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
  </style>
</head>

<body>

  <div class="sidebar">
    <a href="post_home.php" class="sidebar-btn">ホームに戻る</a>
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

    <!-- 左メニュー -->
    <!--
    <div class="sidebar-left">
      <a href="./post_add.php">ピ</a>
    </div>
    -->

    <!-- メインフォーム -->
    <main>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-box">
          <h2>掲示板に投稿</h2>


          <textarea name="content" placeholder="メッセージを入力..." required></textarea>

          <div class="upload-section">
            <label>画像をアップロード：</label>
            <input type="file" name="image">
          </div>

          <button type="submit" class="submit-btn">投稿する</button>


        </div>

      </form>
    </main>

    <!-- 右側メニュー -->
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