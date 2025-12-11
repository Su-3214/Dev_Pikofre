<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php"; 

$_SESSION['u_id'] = 1;

// URLから game_id を取得
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* ------------------------------
   ゲーム名の取得
-------------------------------- */
$sql_game = "SELECT game_name FROM game_type WHERE game_id = ?";
$stmt_game = $pdo->prepare($sql_game);
$stmt_game->execute([$game_id]);
$game = $stmt_game->fetch(PDO::FETCH_ASSOC);
$game_name = $game ? $game['game_name'] : "ゲーム";

/* ------------------------------
   ゲーム別ヘッダー画像（左サイド用）
-------------------------------- */
$gameImages = [
    1000 => "/images/game/apex_header.png",
    2000 => "/images/game/valorant_header.png",
    3000 => "/images/game/overwatch2_header.png",
    4000 => "/images/game/minecraft_header.png",
    5000 => "/images/game/splatoon3_header.png",
];

$header_img = isset($gameImages[$game_id]) ? $gameImages[$game_id] : "/images/default_game.png";

/* ------------------------------
   攻略記事（ピックアップ用）
-------------------------------- */
$sql_info = "SELECT * FROM game_info WHERE game_id = ? ORDER BY update_date DESC LIMIT 8";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->execute([$game_id]);
$infos = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------------
   募集
-------------------------------- */
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = ? ORDER BY recruit_start DESC LIMIT 6";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->execute([$game_id]);
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------------
   掲示板
-------------------------------- */
$sql_post = "SELECT * FROM piko_post WHERE game_id = ? ORDER BY post_date DESC LIMIT 6";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute([$game_id]);
$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------------
   右側メニュー
-------------------------------- */
$rightMenu = [
    "攻略記事" => "kouryaku.php?id={$game_id}",
    "募集"     => "recruit.php?id={$game_id}",
    "掲示板"   => "keijiban.php?id={$game_id}",
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game_name) ?> ホーム</title>

    <!-- CSS -->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/gamehome.css"> <!-- 最後 -->

</head>

<body>

<!-- ========================= HEADER ========================= -->
<header class="site-header">
    <div class="site-brand">
        <img src="/images/pikologo.png" alt="PikoPikoFriends">
        <div class="site-title">PikoPikoFriends</div>
    </div>

    <div class="header-actions">
        <div class="icon-bell">
            <svg width="22" height="22" viewBox="0 0 24 24">
                <path d="M15 17H9a3 3 0 0 1-3-3V11a6 6 0 0 1 12 0v3a3 3 0 0 1-3 3z" stroke="#111"/>
                <path d="M11 20h2" stroke="#111"/>
            </svg>
        </div>

        <a class="logout-link" href="logout.php">ログアウト</a>
        <div class="avatar-dot"></div>
    </div>
</header>

<div class="content-wrap">

<!-- ========================= LEFT SIDEBAR ========================= -->
<aside class="left-sidebar">

    <!-- ゲーム画像 -->
    <div class="game-header-img">
        <img src="<?= $header_img ?>" alt="<?= htmlspecialchars($game_name) ?>">
    </div>

    <!-- タイトル -->
    <h3 class="left-title">人気な<?= htmlspecialchars($game_name) ?>記事</h3>

    <ul class="left-article-list">
        <?php foreach ($infos as $info): ?>
            <li>
                <a href="kouryaku_detail.php?id=<?= $info['info_id'] ?>">
                    <?= htmlspecialchars($info['info_title'] ?: mb_substr($info['info_detail'], 0, 30)) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</aside>

<!-- ========================= MAIN CONTENT ========================= -->
<main class="main">

    <div class="container">

        <!-- 最新の記事（大枠） -->
        <div class="latest-box">
            <div class="title">最新の<?= htmlspecialchars($game_name) ?>記事</div>

            <?php if (!empty($infos)): 
                $top = $infos[0];
                $top_title = $top['info_title'] ?: mb_substr($top['info_detail'], 0, 100);
            ?>
                <div class="latest-body">
                    <div class="latest-thumb">
                        <?= !empty($top['info_image']) 
                            ? '<img src="'.htmlspecialchars($top['info_image']).'" style="width:100%;height:100%;object-fit:cover;">'
                            : 'サムネ'
                        ?>
                    </div>
                    <div class="latest-text"><?= htmlspecialchars($top_title) ?></div>
                </div>
            <?php else: ?>
                <p>記事がありません</p>
            <?php endif; ?>
        </div>

        <!-- 募集カード -->
        <?php foreach ($recruits as $recruit): ?>
            <div class="recruit-card">
                <div class="recruit-top">
                    <div class="name"><?= htmlspecialchars($recruit['u_name']) ?></div>
                    <div class="remain">残り<?= $recruit['recruit_number'] ?>人</div>
                </div>

                <div class="recruit-body">
                    <div class="recruit-title">
                        <?= htmlspecialchars(mb_substr($recruit['recruit_title'], 0, 80)) ?>
                    </div>

                    <div class="recruit-detail">
                        <?= nl2br(htmlspecialchars($recruit['recruit_detail'])) ?>
                    </div>

                    <a class="join-btn" href="room_number.php?id=<?= $recruit['recruit_id'] ?>">参加</a>
                </div>

            </div>
        <?php endforeach; ?>

        <!-- 掲示板 -->
        <div class="post-style">
            <?php foreach ($posts as $post): ?>
                <div class="post-row">
                    <div class="post-thumb">
                        <?php if (!empty($post['post_image'])): ?>
                            <img src="<?= htmlspecialchars($post['post_image']) ?>">
                        <?php endif; ?>
                    </div>

                    <div class="post-desc">
                        <?= nl2br(htmlspecialchars(mb_substr($post['post_detail'], 0, 260))) ?>
                        <div class="post-date"><?= htmlspecialchars($post['post_date']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</main>

<!-- ========================= RIGHT SIDEBAR ========================= -->
<aside class="right-sidebar">

    <?php foreach ($rightMenu as $label => $link): ?>
        <a class="side-btn" href="<?= $link ?>"><?= $label ?></a>
    <?php endforeach; ?>

</aside>

</div>

<!-- ========================= FOOTER ========================= -->
<footer>
    <nav>
        <a href="home.php">ホーム</a> |
        <a href="recruit.php">募集</a> |
        <a href="keijiban.php">掲示板</a> |
        <a href="kouryaku.php">攻略</a>
    </nav>
    <p>copyright chlorine 2025</p>
</footer>

</body>
</html>
