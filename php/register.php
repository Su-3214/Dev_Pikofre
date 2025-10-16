<?php
$errors = [];
//ファイルの読み込み
require_once "db_connect.php";
require_once "functions.php";

//セッションの開始
session_start();

//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'u_name'  => '',
    'u_mail' => '',
    'u_password'  => '',
    'confirm_password'  => ''//確認用に置いている。
];

//GET通信だった場合はセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}
//POST通信だった場合はDBへの新規登録処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //CSRF対策
    try{
    checkToken();
    }catch(Exception $e){
        //checktoken確認用
        echo 'Invalid Token.';
        exit;
    }

    // POSTされてきたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    // バリデーション
    $errors = validation($datas);

    //データベースの中に同一ユーザー名が存在していないか確認
     if (empty($errors['u_name']) && empty($errors['u_mail'])) {
        $sql = "SELECT u_id FROM user WHERE u_name = :u_name OR u_mail = :u_mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':u_name', $datas['u_name'], PDO::PARAM_STR);
        $stmt->bindValue(':u_mail', $datas['u_mail'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors['u_name'] = 'このユーザー名またはメールアドレスは既に登録されています。';
        }
    }
    //エラーがなかったらDBへの新規登録を実行
    if(empty($errors)){
        //today作成
        $today = date('Y-m-d');
        $params = [
            'u_id' =>null,
            'u_name'=>$datas['u_name'],
            'u_password'=>password_hash($datas['password'], PASSWORD_DEFAULT),
            'u_mail'=>$datas['u_mail'],
            'u_date'=>$today
        ];

        $count = 0;
        $columns = '';
        $values = '';
        foreach (array_keys($params) as $key) {
            if($count > 0){
                $columns .= ',';
                $values .= ',';
            }
            $columns .= $key;
            $values .= ':'.$key;
            $count++;
        }

        $pdo->beginTransaction();//トランザクション処理
        try {
            $sql = 'INSERT INTO user ('.$columns .')values('.$values.')';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pdo->commit();
            header("location: login.php");
            exit;
        } catch (PDOException $e) {
            echo 'ERROR: Could not register.';
            $pdo->rollBack();
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <!-- bootstrap読み込み -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            font: 14px sans-serif;
        }
        .wrapper{
            width: 400px;
            padding: 20px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>新規登録</h2>
        <p>アカウント情報を入力してください</p>
        <form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
            <div class="form-group">
                <label>ユーザー名</label>
                <input type="text" name="u_name" class="form-control <?php echo (!empty(h($errors['u_name']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['u_name']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['u_name']); ?></span>
            </div>
             <div class="form-group">
                <label>メールアドレス</label>
                <input type="text" name="u_mail" class="form-control <?php echo (!empty(h($errors['u_mail']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['u_mail']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['u_mail']); ?></span>
            </div>
            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="u_password" class="form-control <?php echo (!empty(h($errors['u_password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['u_password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['u_password']); ?></span>
            </div>
            <div class="form-group">
                <label>パスワード（再確認）</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty(h($errors['confirm_password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['confirm_password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['confirm_password']); ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                <input type="submit" class="btn btn-primary" value="送信">
            </div>
            <p>すでにアカウントをお持ちですか？ <a href="login.php">ログイン</a>はこちらから</p>
        </form>
    </div>    
</body>
</html>
