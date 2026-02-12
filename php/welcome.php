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
    <!--ここはヘッダーです-->
    <header>
        <img src="../images/pikofre_icon.png" alt="ロゴ" height="100" width="100">
        <h1>Chlorine_Website loggedin</h1>

        <nav class="login_nav">
            <a href="logout.php" class="btn btn-danger ml-3">ログアウト</a>
        </nav>
        <!--<nav class="header_nav">
            <a href="../welcome.php">攻略記事</a>
            <a href="../forum.html">募集</a>
            <a href="gametop.php">掲示板</a></li>
        </nav>　必要ないのでいったん消去してます-->
    </header>
    <!--ここからはメインです-->
    <main>
        <section class="login_main">
            <p>修正版を公開しました。<br>
                以下変更点候補<br>
                ログイン、ログアウトをリンクではなくボタン化する<br>
                ぴこふれアイコンを押下後、トップページに遷移するように<br>
                ヘッダーフッターのリンクを仕込む、ナビの見た目を細かく修正、リンクを仕込む</p>
            <?php

            ?>
        </section>
    </main>
    <!--ここからはフッターです-- phpファイルのため階層構造を意識 -->
    <footer>
        <nav class="footer_nav">
            <a href="#">トップ</a>
            <a href="../forum.html">掲示板</a>
            <a href="gametop.php">ゲームページ</a>
        </nav>
        <p>copyright Chlorine 2025 </p>
    </footer>
    <!--JavaScript-->
    <script src="../javascript/index.js"></script>
    <!--JavaScriptEnd-->
</body>

</html>