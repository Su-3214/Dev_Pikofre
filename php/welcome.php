<?php
session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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
        <h1>Chlorine_Website ログイン済み</h1>
        <nav class="login_nav">
            <a href="php/login.php">ログイン</a>
        </nav>
        <nav class="header_nav">
            <a href="../index.html">トップ</a>
            <a href="../forum.html">掲示板</a>
            <a href="gametop.php">ゲームページ</a></li>
            <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
        </nav>
    </header>
    <!--ここからはメインです-->
    <main>
        <section class="login_main">
            <p>ログインしてください</p>
            <?php
            
            ?>
        </section>
    </main>
    <!--ここからはフッターです-- phpファイルのため階層構造を意識 -->
    <footer>
        <nav class="footer_nav">
            <a href="../index.html">トップ</a>
            <a href="../forum.html">掲示板</a>
            <a href="gametop.php">ゲームページ</a>
        </nav>
        <p>copyright Chlorine 2025 </p>
    </footer>
</body>

</html>