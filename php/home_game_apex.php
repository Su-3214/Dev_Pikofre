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

/* 右メニュー */
$rightMenu = [
    "攻略記事" => "Strategyhome.php?id={$game_id}",
    "募集" => "recruit_home.php?id={$game_id}",
    "掲示板" => "post_home.php?id={$game_id}",
];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($game_name) ?> ホーム</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <div class="content-wrap">

        <!-- 左 -->
        <aside class="left-pickup">
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
            <div class="container">

                <!-- 最新記事 -->
                <div class="latest-box">
                    <div class="title">最新の<?= htmlspecialchars($game_name) ?>記事</div>
                    <?php if (!empty($infos[0])):
                        $top = $infos[0];
                        $top_title = isset($top['info_title']) && $top['info_title'] !== '' ? $top['info_title'] : mb_substr($top['info_detail'], 0, 100);
                        $thumb = isset($top['info_image']) && $top['info_image'] !== '' ? $top['info_image'] : null;
                    ?>
                        <div class="latest-body">
                            <div class="latest-thumb">
                                <?php if ($thumb): ?>
                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="thumb" style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <div class="article-box dummy">記事がありません</div>
                                <?php endif; ?>
                            </div>
                            <div class="latest-text">
                                <a href="detail.php?id=<?= $top['info_id'] ?>" style="color:inherit;text-decoration:none;">
                                    <?= htmlspecialchars($top_title) ?>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div>記事がありません</div>
                    <?php endif; ?>
                </div>

                <!-- 募集 -->
                <?php if ($recruits): ?>
                    <?php foreach ($recruits as $r): ?>
                        <div class="recruit-card">
                            <div class="recruit-top">
                                <div class="name"><?= htmlspecialchars($r['u_name']) ?></div>
                                <div class="icon"></div>
                                <div style="margin-left:auto; font-size:12px; color:#333;">
                                    参加中 <?= htmlspecialchars($r['recruit_number']) ?>
                                </div>
                            </div>

                            <div class="recruit-body">
                                
                                <div class="recruit-detail">
                                    <?= nl2br(htmlspecialchars($r['recruit_detail'])) ?>
                                </div>

                                <?php if (!empty($r['recruit_vc'])): ?>
                                    <p class="recruit-vc">VC: <?= htmlspecialchars($r['recruit_vc']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($r['recruit_discord'])): ?>
                                    <p class="recruit-discord">Discord: <?= htmlspecialchars($r['recruit_discord']) ?></p>
                                <?php endif; ?>

                                <form action="recruit_room_number.php" method="post">
                                    <input type="hidden" name="recruit_id" value="<?= $r['recruit_id'] ?>">
                                    <input type="submit" class="join-btn" value="参加">
                                </form>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>現在募集はありません。</p>
                <?php endif; ?>


                <!-- 投稿 -->
                <div class="post-style">
                    <?php if ($posts): ?>
                        <?php foreach ($posts as $p): ?>
                            <div class="post-row" style="margin-bottom:12px;">
                                <div class="post-thumb">
                                    <?php if (!empty($p['post_image'])): ?>
                                        <img src="<?= htmlspecialchars($p['post_image']) ?>" style="width:100%;height:100%;object-fit:cover;">
                                    <?php endif; ?>
                                </div>
                                <div class="post-desc">
                                    <div style="font-weight:bold;margin-bottom:4px;"><?= htmlspecialchars($p['u_name'] ?? 'プレイヤー') ?></div>
                                    <?= nl2br(htmlspecialchars(mb_substr($p['post_detail'], 0, 260))) ?>
                                    <div style="font-size:12px; color:#ddd; margin-top:8px;">
                                        <?= htmlspecialchars($p['post_date'] ?? '') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>現在投稿はありません。</p>
                    <?php endif; ?>
                </div>

            </div>
        </main>

        <!-- 右メニュー
        <aside class="sidebar-right">
            <h4>メニュー</h4>
            <ul class="game-list">
                <?php foreach ($rightMenu as $label => $link): ?>
                    <li><a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </aside> -->
    </div>
    <script src="../javascript/index.js"></script>
</body>

</html>