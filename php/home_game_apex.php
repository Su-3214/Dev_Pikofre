<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php";


/* game_id */
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 50000;

/* ゲーム名 */
$sql_game = "SELECT game_name FROM game_type WHERE game_id = ?";
$stmt_game = $pdo->prepare($sql_game);
$stmt_game->execute([$game_id]);
$game = $stmt_game->fetch(PDO::FETCH_ASSOC);
$game_name = $game ? $game['game_name'] : "APEX";

/* 左サイド画像 */
$gameImages = [
    50000 => "/images/game/apex_header.png",
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
$sql_recruit = "SELECT * FROM game_recruitment WHERE game_id = ? ORDER BY recruit_start DESC LIMIT 3";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->execute([$game_id]);
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

/* 掲示板 */
$sql_post = "SELECT * FROM piko_post WHERE game_id = ? ORDER BY post_date DESC LIMIT 3";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute([$game_id]);
$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($game_name) ?> ホーム</title>
    <link rel="stylesheet" href="../css/gamehome.css">
</head>

<body>
    <div class="content-wrap">

        <!-- 左 -->
        <aside class="left-sidebar">
            <img class="game-header" src="<?= $header_img ?>">
            <h3>人気な<?= htmlspecialchars($game_name) ?>記事</h3>

            <ul>
                <?php if ($infos): foreach ($infos as $info): ?>
                        <li><?= htmlspecialchars($info['info_title'] ?? mb_substr($info['info_detail'], 0, 20)) ?></li>
                    <?php endforeach;
                else: ?>
                    <li>記事がありません</li>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- 中央 -->
        <main class="main">

            <!-- 最新記事 -->
            <section class="latest-article">
                <h2>最新の<?= htmlspecialchars($game_name) ?>記事</h2>

                <?php if ($infos): ?>
                    <?php foreach ($infos as $index => $info): ?>
                        <div class="article-box">
                            <img src="<?= $info['info_image'] ?? '/images/sample_thumb.png' ?>">
                            <p><a href="detail.php?id=<?= $info['info_id'] ?>"><?= htmlspecialchars($info['info_title'] ?? $info['info_detail']) ?></a></p>
                        </div>
                        <?php if ($index >= 2) break; // 3件まで表示 
                        ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="article-box dummy">記事がありません</div>
                <?php endif; ?>
            </section>

            <!-- 募集 -->
            <section class="recruit">
                <h2>最新の募集</h2>
                <?php if ($recruits): ?>
                    <?php foreach ($recruits as $r): ?>
                        <div class="recruit-card">
                            <div class="recruit-head">
                                <span class="name"><?= htmlspecialchars($r['u_name']) ?></span>
                                <span class="count">参加中 <?= htmlspecialchars($r['recruit_number']) ?></span>
                            </div>

                            <p class="recruit-title"><?= htmlspecialchars($r['recruit_title']) ?></p>

                            <p class="recruit-text">
                                <?= nl2br(htmlspecialchars($r['recruit_detail'])) ?>
                            </p>

                            <?php if (!empty($r['recruit_vc'])): ?>
                                <p class="recruit-vc">VC: <?= htmlspecialchars($r['recruit_vc']) ?></p>
                            <?php endif; ?>

                            <form action="recruit_room_number.php" method="post">
                                <input type="hidden" name="recruit_id" value="<?= $r['recruit_id'] ?>">
                                <input type="submit" class="join" value="参加">
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>現在募集はありません。</p>
                <?php endif; ?>
            </section>


            <!-- 投稿 -->
            <section class="post">
                <h2>最新の投稿</h2>
                <?php if ($posts): ?>
                    <?php foreach ($posts as $p): ?>
                        <div class="post-card">
                            <div class="post-head">
                                <div class="icon"></div>
                                <span><?= htmlspecialchars($p['u_name'] ?? 'プレイヤー') ?></span>
                            </div>
                            <?php if (!empty($p['post_image'])): ?>
                                <img src="<?= htmlspecialchars($p['post_image']) ?>">
                            <?php endif; ?>
                            <p><?= nl2br(htmlspecialchars($p['post_detail'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>現在投稿はありません。</p>
                <?php endif; ?>
            </section>

        </main>
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>