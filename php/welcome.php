<?php
session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- ヘッダーはJSで生成されます -->
    <main>
        <section class="login_main">
            <p>修正版を公開しました。<br>
                ぴこふれアイコンを押下後、トップページに遷移するように<br>
                ヘッダーフッターのリンクを仕込む、ナビの見た目を細かく修正、リンクを仕込む</p>
        </section>
    </main>

    <!-- フッターはJSで生成されます -->
    <script src="../javascript/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>