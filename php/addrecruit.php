<?php
//セッションスタート
session_start();
//ファイルの読み込み
require_once "db_connect.php";

//テスト用にセッションに値を追加
    $_SESSION['game_id'] = 50000;
    $_SESSION['u_id'] = 1;


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
      } 
    catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。";
    exit;
    }

    //sql実行で得た情報の取得処理
    $recruit_user = $stmt_addrecruit->fetchAll(PDO::FETCH_ASSOC);


//POSTされたデータからINSERT処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $recruit_vc = $_POST['recruit_vc'] ?? null;
    $recruit_number = $_POST['recruit_number'] ?? null;
    $recruit_detail = $_POST['recruit_detail'] ?? null;
}
//インサート用のsql文
if ($game_id && $u_id && $recruit_vc && $recruit_number) {
        $sql = "INSERT INTO game_recruitment 
                (game_id, u_id, u_name,recruit_vc, recruit_number, recruit_detail, )
                VALUES (:game_id, :u_id, :u_name,:recruit_vc, :recruit_number, :recruit_detail)";
        $stmt = $pdo->prepare($sql);
        $stmt ->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt ->bindParam(':u_id', $u_id, PDO::PARAM_INT);
        $stmt ->bindParam(':u_name', $u_name, PDO::PARAM_STR);
        $stmt ->bindParam(':recruit_vc', $recruit_vc, PDO::PARAM_STR);
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
</head>
<body>
  

  
      <img src="apex.jpg" alt="Apex Legends">
      <h3>Apex Legends 募集一覧</h3>
      <button style="background:#ffa500;">募集</button>
   

   
      <form action="" method="POST">
        
          <span>遊ぶ人数</span>
          <select name="recruit_number" required>
            <option value="2">二人募集</option>
            <option value="3">三人募集</option>
            <option value="4">四人募集</option>
            <option value="5">五人募集</option>
          </select>
        <br>

       
          <span>ボイスチャット</span>
          <select name="recruit_vc" required>
            <option value="必須">必須</option>
            <option value="どちらでも">どちらでも</option>
            <option value="なし">なし</option>
          </select>
        <br>

        <textarea name="recruit_detail" placeholder="募集の詳細を入力してください..." required></textarea>
        <br>

        <input type="hidden" name="u_name" value="<?php echo $recruit_user['u_name'] ?>">
        <button type="submit" >作成する</button>
      </form>
    </div>
  </div>
</body>
</html>