<?php
session_start();
require_once __DIR__ . '/php/db_connect.php';
require_once __DIR__ . '/php/functions.php';

// テスト用ログイン（本来は login.php）
if (!isset($_SESSION['u_id'])) {
    $_SESSION['u_id'] = 1;
}
$u_id = (int)$_SESSION['u_id'];

// ユーザー情報
$stmt = $pdo->prepare("SELECT u_id, u_name FROM user WHERE u_id=?");
$stmt->execute([$u_id]);
$user = $stmt->fetch();

// プロフィール画像（まだ user 表には avatar が無いので仮）
$avatar = "images/pikofure_icon.png";

// 攻略記事 = game_review
$articles = $pdo->query("
    SELECT review_id, game_name, review_detail, review_date 
    FROM game_review 
    ORDER BY review_id DESC LIMIT 10
")->fetchAll();

// ゲーム一覧 = game_type or game_info の game_name
$games = $pdo->query("
    SELECT game_id, game_name
    FROM game_type
")->fetchAll();

// 最新投稿（募集 + 掲示板 + レビュー）
$latest = $pdo->query("
    (
        SELECT recruit_id AS id, recruit_detail AS text, recruit_start AS date, '募集' AS type
        FROM game_recruitment
    )
    UNION ALL
    (
        SELECT post_id AS id, post_detail AS text, post_date AS date, '掲示板' AS type
        FROM piko_post
    )
    UNION ALL
    (
        SELECT review_id AS id, review_detail AS text, review_date AS date, '攻略' AS type
        FROM game_review
    )
    ORDER BY date DESC
    LIMIT 10
")->fetchAll();

// 通知（仮）notifications テーブルが無いので “未実装”
$unreadCount = 0;
$notifications = [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PikoPikoFriends</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>chlorine_Website</h1>
    <nav class="login_nav">
        <a href="php/login.php">ログイン</a>
    </nav>

    <nav class="header_nav">
        <a href="index.php">トップ</a>
        <a href="forum.html">掲示板</a>
        <a href="gametop.php">ゲームページ</a>
    </nav>

    <div class="header-right">
        <button id="notifBtn">🔔 <span><?= $unreadCount ?></span></button>
        <a href="php/logout.php">ログアウト</a>

        <div class="profile">
            <img src="<?= $avatar ?>" width="32">
            <span><?= htmlspecialchars($user['u_name']) ?></span>
        </div>
    </div>
</header>

<main>
<div class="container">

    <!-- 左サイドバー（攻略記事） -->
    <aside class="left">
        <h2>攻略記事</h2>
        <ul>
            <?php foreach ($articles as $a): ?>
                <li>
                    <a href="guide.php?id=<?= $a['review_id'] ?>">
                        <?= htmlspecialchars(mb_substr($a['review_detail'], 0, 20)) ?>...
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- メイン（最新投稿） -->
    <section class="center">
        <h2>最新の投稿</h2>

        <?php if (empty($latest)): ?>
            <p>投稿がありません。</p>
        <?php else: ?>
            <?php foreach ($latest as $p): ?>
                <div class="post-card">
                    <span class="badge"><?= $p['type'] ?></span>
                    <p><?= htmlspecialchars(mb_substr($p['text'], 0, 50)) ?>...</p>
                    <small><?= $p['date'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- 右サイドバー（ゲーム一覧） -->
    <aside class="right">
        <h2>ゲーム一覧</h2>
        <ul>
            <?php foreach ($games as $g): ?>
                <li><a href="game.php?id=<?= $g['game_id'] ?>">
                    <?= htmlspecialchars($g['game_name']) ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

</div>
</main>

<footer>
    <nav class="footer_nav">
        <a href="index.php">トップ</a>
        <a href="recruit.php">募集</a>
        <a href="forum.html">掲示板</a>
        <a href="gametop.php">ゲームページ</a>
    </nav>
    <p>copyright chlorine 2025</p>
</footer>

<script src="javascript/index.js"></script>
</body>
</html>
