<?php
//デバッグ　
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
//ファイルの読み込み
require_once "db_connect.php";
require_once "functions.php";
//セッション開始
session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'u_mail' => '',
    'u_password' => '',
    //password confirm 'confirm_password'  => ''
];
$errors = [];
$login_err = "";
//CSRF対策↓
//GET通信だった場合はセッション変数にトークンを追加
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    setToken();
}

//POST通信だった場合はログイン処理を開始
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ////CSRF対策
    checkToken();
    // POSTされてきたデータを受け取り、$datas[]変数に格納
    foreach ($datas as $key => $value) {
        $value = filter_input(INPUT_POST, $key, FILTER_DEFAULT);
        if ($value !== null) {
            $datas[$key] = $value;
        }
    }
}

// バリデーションログインを参照し利用する
$errors = validationlogin($datas, false);
if (empty($errors)) {
    //メールアドレスから該当するユーザー情報を取得
    $sql = "SELECT * FROM user WHERE u_mail = :u_mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':u_mail', $datas['u_mail'], PDO::PARAM_STR);
    $stmt->execute();
    //ユーザー情報があればチェック
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($datas['u_password'], $row['u_password'])) {
            session_regenerate_id(true);
            $_SESSION["loggedin"] = true;
            $_SESSION["u_id"] = $row['u_id'];
            header("location:home.php");
            exit;
        } else {
            $login_err = 'メールアドレスまたはパスワードが違います。';
        }
    } else {
        $login_err = 'メールアドレスまたはパスワードが違います。';
    }
}
/*else{
   print("エラーあり：");
   print_r($errors);
}エラー確認のために残している*/

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <!--<?php print_r($_SESSION) ?>
    <?php print_r($datas) ?> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 400px;
            padding: 20px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>ログイン</h2>
        <p>メールアドレスとパスワードを入力してください</p>

        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
            <div class="form-group">
                <label>メールアドレス</label>
                <input type="text" name="u_mail" placeholder="example@mailadress.com"
                    class="form-control <?php echo (!empty($errors['u_mail']) ? 'is-invalid' : ''); ?>"
                    value="<?php echo isset($datas['u_mail']) ? h($datas['u_mail']) : ''; ?>">
                <span
                    class="invalid-feedback"><?php echo isset($errors['u_mail']) ? h($errors['u_mail']) : ''; ?></span>
            </div>

            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="u_password" placeholder="examplepassword"
                    class="form-control <?php echo (!empty($errors['u_password']) ? 'is-invalid' : ''); ?>"
                    value="<?php echo isset($datas['u_password']) ? h($datas['u_password']) : ''; ?>">
                <span
                    class="invalid-feedback"><?php echo isset($errors['u_password']) ? h($errors['u_password']) : ''; ?></span>
            </div>

            <div class="form-group">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                <input type="submit" class="btn btn-primary" value="ログイン">
            </div>
            <p>アカウントをお持ちでない方はここから<a href="register.php">サインアップ</a>してください</p>
        </form>
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>