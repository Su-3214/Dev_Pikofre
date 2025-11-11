<?php
//ファイルの読み込み
require_once "db_connect.php";

//セッションでユーザーidとゲームidを取得
    $u_id = $_SESSION['u_id'];
    $game_id = $_SESSION['game_id'];


//POSTされたデータからINSERT処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $recruit_vc = $_POST['recruit_vc'] ?? null;
    $recruit_number = $_POST['recruit_number'] ?? null;
    $recruit_detail = $_POST['recruit_detail'] ?? null;
}
//インサート用のsql文
if ($game_id && $u_id && $recruit_vc && $recruit_number) {
        $sql = "INSERT INTO game_recruitment 
                (game_id, u_id, recruit_vc, recruit_number, recruit_detail, )
                VALUES (:game_id, :u_id, :recruit_vc, :recruit_number, :recruit_detail)";
        $stmt = $pdo->prepare($sql);
        $stmt ->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt ->bindParam(':u_id', $u_id, PDO::PARAM_INT);
        $stmt ->bindParam(':game_id', $game_id, PDO::PARAM_STR);
        $stmt ->bindParam(':recruit_number', $recruit_number, PDO::PARAM_INT);
        $stmt ->bindParam(':recruit_detail', $recruit_detail, PDO::PARAM_STR);

        try {
            $stmt->execute();
            echo "<script>alert('募集を作成しました！');</script>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>登録に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>必須項目が未入力です。</p>";
    }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>募集作成画面</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #e6e6fa;
      margin: 0;
      padding: 0;
    }
    header {
      background: linear-gradient(to right, #4b0082, #5f2da0);
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-weight: bold;
      font-size: 22px;
      color: #a3d4ff;
    }
    .container {
      display: flex;
      margin-top: 20px;
    }
    .sidebar {
      width: 220px;
      background-color: white;
      border-radius: 10px;
      margin-left: 20px;
      padding: 15px;
    }
    .sidebar img {
      width: 100%;
      border-radius: 10px;
    }
    .main {
      flex: 1;
      background-color: white;
      border-radius: 15px;
      margin: 0 20px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .label {
      background-color: black;
      color: white;
      padding: 8px;
      border-radius: 8px 0 0 8px;
      display: inline-block;
      width: 120px;
      text-align: center;
    }
    select, textarea, button, input {
      border-radius: 8px;
      padding: 8px;
      border: none;
    }
    select {
      background-color: #00bfff;
      color: white;
      font-weight: bold;
    }
    textarea {
      width: 100%;
      height: 100px;
      margin-top: 10px;
    }
    .btn {
      background-color: #00bfff;
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      display: block;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  

  <div class="container">
    <div class="sidebar">
      <img src="apex.jpg" alt="Apex Legends">
      <h3>Apex Legends 募集一覧</h3>
      <button class="btn" style="background:#ffa500;">募集</button>
    </div>

    <div class="main">
      <form action="" method="POST">
        <div>
          <span class="label">遊ぶ人数</span>
          <select name="recruit_number" required>
            <option value="2">二人募集</option>
            <option value="3">三人募集</option>
          </select>
        </div>
        <br>

        <div>
          <span class="label">ボイスチャット</span>
          <select name="recruit_vc" required>
            <option value="必須">必須</option>
            <option value="どちらでも">どちらでも</option>
            <option value="なし">なし</option>
          </select>
        </div>
        <br>

        <textarea name="recruit_detail" placeholder="募集の詳細を入力してください..." required></textarea>
        <br>

        <button type="submit" class="btn">作成する</button>
      </form>
    </div>
  </div>
</body>
</html>