<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php";

$_SESSION['u_id'] = 1;

/* game_id */
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 1000;

/* ゲーム名 */
$sql_game = "SELECT game_name FROM game_type WHERE game_id = ?";
$stmt_game = $pdo->prepare($sql_game);
$stmt_game->execute([$game_id]);
$game = $stmt_game->fetch(PDO::FETCH_ASSOC);
$game_name = $game ? $game['game_name'] : "APEX";

/* 左サイド画像 */
$gameImages = [
    1000 => "/images/game/apex_header.png",
    2000 => "/images/game/valorant_header.png",
    3000 => "/images/game/overwatch2_header.png",
];
$header_img = $gameImages[$game_id] ?? "/images/default_game.png";

/* 攻略記事 */
$sql_info = "SELECT * FROM game_info WHERE game_id = ? ORDER BY update_date DESC LIMIT 5";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->execute([$game_id]);
$infos = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

/* 募集 */
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = ? ORDER BY recruit_start DESC LIMIT 1";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->execute([$game_id]);
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

/* 掲示板 */
$sql_post = "SELECT * FROM piko_post WHERE game_id = ? ORDER BY post_date DESC LIMIT 1";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute([$game_id]);
$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);

/* 右メニュー */
$rightMenu = [
    "攻略記事" => "kouryaku.php?id={$game_id}",
    "募集" => "recruit.php?id={$game_id}",
    "掲示板" => "keijiban.php?id={$game_id}",
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($game_name) ?> ホーム</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/gamehome.css">
</head>

<body>

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

<div class="content-wrap">

<!-- 左 -->
<aside class="left-sidebar">
    <img class="game-header" src="<?= $header_img ?>">
    <h3>人気な<?= htmlspecialchars($game_name) ?>記事</h3>

    <ul>
        <?php if ($infos): foreach ($infos as $info): ?>
            <li><?= htmlspecialchars($info['info_title'] ?? mb_substr($info['info_detail'],0,20)) ?></li>
        <?php endforeach; else: ?>
            <li>記事がありません</li>
        <?php endif; ?>
    </ul>
</aside>

<!-- 中央 -->
<main class="main">

<!-- 最新記事 -->
<section class="latest-article">
    <h2>最新の<?= htmlspecialchars($game_name) ?>記事</h2>

    <?php if ($infos): $top = $infos[0]; ?>
        <div class="article-box">
            <img src="<?= $top['info_image'] ?? '/images/sample_thumb.png' ?>">
            <p><?= htmlspecialchars($top['info_title'] ?? $top['info_detail']) ?></p>
        </div>
    <?php else: ?>
        <div class="article-box dummy">記事がありません</div>
    <?php endif; ?>
</section>

<!-- 募集 -->
<section class="recruit">
<?php if ($recruits): $r=$recruits[0]; ?>
    <div class="recruit-card">
        <div class="recruit-head">
            <span class="name"><?= htmlspecialchars($r['u_name']) ?></span>
            <span class="count">参加中 <?= htmlspecialchars($r['recruit_number']) ?>/3</span>
        </div>

        <p class="recruit-title"><?= htmlspecialchars($r['recruit_title']) ?></p>

        <p class="recruit-text">
            <?= nl2br(htmlspecialchars($r['recruit_detail'])) ?>
        </p>

        <!-- ★ ここが修正点 -->
        <a class="join" href="room_number.php?recruit_id=<?= $r['recruit_id'] ?>">
            参加
        </a>
    </div>

<?php else: ?>
    <!-- ダミー -->
    <div class="recruit-card">
        <div class="recruit-head">
            <span class="name">Padplayer</span>
            <span class="count">参加中 1/3</span>
        </div>

        <p class="recruit-title">マスター目指してます</p>

        <p class="recruit-text">
            主ダイヤ1 / VC可 / 18↑<br>
            雰囲気重視
        </p>

        <a class="join" href="room_number.php">
            参加
        </a>
    </div>
<?php endif; ?>
</section>


<!-- 投稿 -->
<section class="post">
<?php if ($posts): $p=$posts[0]; ?>
    <div class="post-card">
        <div class="post-head">
            <div class="icon"></div>
            <span><?= $p['u_name'] ?? 'プレイヤー' ?></span>
        </div>
        <img src="<?= $p['post_image'] ?? '/images/sample_post.png' ?>">
        <p><?= $p['post_detail'] ?></p>
    </div>
<?php else: ?>
    <!-- ダミー -->
    <div class="post-card">
        <div class="post-head">
            <div class="icon"></div>
            <span>さすらいのプレイヤー</span>
        </div>
        <img src="/images/sample_post.png">
        <p>ライフラインの目がキマりすぎてるwwww</p>
    </div>
<?php endif; ?>
</section>

</main>

<!-- 右 -->
<aside class="right-sidebar">
<?php foreach ($rightMenu as $label=>$link): ?>
    <a class="side-btn" href="<?= $link ?>"><?= $label ?></a>
<?php endforeach; ?>
</aside>

</div>

<footer>
    <nav>ホーム | 募集 | 掲示板 | 攻略</nav>
    <p>copyright chlorine 2025</p>
</footer>

</body>
</html>
