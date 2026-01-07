<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php";

$_SESSION['u_id'] = 1;

/* recruit_id */
$recruit_id = isset($_GET['recruit_id']) ? (int)$_GET['recruit_id'] : 0;

/* 募集情報取得 */
$sql = "
    SELECT r.*, u.u_name
    FROM game_recruitment r
    LEFT JOIN user u ON r.u_id = u.u_id
    WHERE r.recruit_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$recruit_id]);
$recruit = $stmt->fetch(PDO::FETCH_ASSOC);

/* ダミー用 */
if (!$recruit) {
    $recruit = [
        'discord_room' => '〇〇番',
        'u_name' => '自称プレデター',
        'recruit_detail' => 'プレデターですキャリーします！'
    ];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>募集ルーム</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/roomnumber.css">
</head>

<body>

<!--
<header class="site-header">
    <div class="site-brand">
        <img src="/images/pikologo.png">
        <span>PikoPikoFriends</span>
    </div>
    <div class="header-actions">
        <span class="bell">🔔</span>
        <a href="logout.php" class="logout">ログアウト</a>
        <div class="avatar"></div>
    </div>
</header>
-->

<div class="content-wrap">

<!-- LEFT -->
<aside class="left-sidebar">
    <img src="/images/game/apex_header.png">
    <h3>Apex Legends<br>募集一覧</h3>

<div class="make-recruit">
    <a href="recruit_make.php" class="recruit-image-btn">
        <img src="../images/pikoru.png" alt="募集作成">
    </a>
</div>



</aside>

<!-- MAIN -->
<main class="main">

    <div class="room-box">

        <div class="room-row">
            <div class="label">Discord部屋番号</div>
            <div class="value"><?= htmlspecialchars($recruit['discord_room']) ?></div>
        </div>

        <div class="room-row">
            <div class="label">ホストユーザー</div>
            <div class="value"><?= htmlspecialchars($recruit['u_name']) ?></div>
        </div>

        <div class="room-detail">
            <h3>募集内容</h3>
            <p><?= nl2br(htmlspecialchars($recruit['recruit_detail'])) ?></p>
        </div>

        <a class="discord-link" href="#">
            Discordサーバーに未参加の方はこちら
        </a>

    </div>

</main>

<!-- RIGHT -->
<aside class="right-sidebar">
    <a class="side-btn" href="kouryaku.php">攻略記事</a>
    <a class="side-btn" href="recruit.php">募集</a>
    <a class="side-btn" href="keijiban.php">掲示板</a>
</aside>

</div>

<!--
<footer>
    <p>copyright chlorine 2025</p>
</footer>
-->

    <script src="../javascript/index.js"></script>
</body>
</html>
