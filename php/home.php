<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php";

$_SESSION['u_id'] = 1;
$_SESSION['game_id'] = 50000;

// 攻略記事（左ピックアップにも使う）
$sql_info = "SELECT * FROM game_info ORDER BY update_date DESC LIMIT 8";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->execute();
$infos = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

// 募集
$sql_recruit = "SELECT * FROM game_recruitment ORDER BY recruit_start DESC LIMIT 6";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->execute();
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

// 掲示板（ポスト）
$sql_post = "SELECT * FROM piko_post ORDER BY post_date DESC LIMIT 6";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute();
$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);

// ▼ ゲーム一覧（右サイドバー用）
$games = [
    "Apex Legends" => "home_game_apex.php",
    "Minecraft" => "game_minecraft.php",
    "Valorant" => "game_valorant.php",
    "モンスト" => "game_monst.php",
    "Overwatch2" => "game_overwatch2.php",
    "マリオカートワールド" => "game_mariokart.php",
    "モンハンワイルズ" => "game_mhw.php",
    "shadowverse Worlds Beyond" => "game_sv.php",
    "ポケポケ" => "game_pokepoke.php",
    "ポケモンGO" => "game_pokemongo.php",
    "スプラトゥーン3" => "game_splatoon3.php",
    "ポケモンSV" => "game_pokemon_sv.php",
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ホーム画面</title>
    <link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/home.css">

</head>
<body>

<!--JS生成予定-->


<div class="content-wrap">

    <!-- 左：ピックアップ -->
    <aside class="left-pickup">
        <h3>ピックアップ</h3>
        <ul class="pickup-list">
            <?php foreach ($infos as $info): ?>
                <?php
                    // 見出し（長すぎる場合は短縮）
                    $title = isset($info['info_title']) && $info['info_title'] !== '' ? $info['info_title'] : (isset($info['info_detail']) ? mb_substr($info['info_detail'], 0, 28) : '記事');
                ?>
                <li><a href="detail.php?info_id=<?= htmlspecialchars($info['info_id']) ?>"><?= htmlspecialchars($title) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- 中央：メイン -->
    <main class="main">
        <div class="container">

            <!-- 最新の記事 上部ボックス -->
            <div class="latest-box">
                <div class="title">最新の記事</div>
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
                                サムネ
                            <?php endif; ?>
                        </div>
                        <div class="latest-text"><?= htmlspecialchars($top_title) ?></div>
                    </div>
                <?php else: ?>
                    <div>記事がありません</div>
                <?php endif; ?>
            </div>

            <!-- 募集カード（複数） -->
            <?php foreach ($recruits as $recruit): ?>
                <div class="recruit-card">
                    <div class="recruit-top">
                        <div class="name"><?= htmlspecialchars($recruit['u_name'] ?? 'ユーザー') ?></div>
                        <div class="icon"></div>
                        <div style="margin-left:auto; font-size:12px; color:#333;">
                            残り<?= htmlspecialchars($recruit['recruit_number'] ?? '0') ?>人
                        </div>
                    </div>

                    <div class="recruit-body">
                        <div style="margin-bottom:8px; color:#fff; font-weight:700;">
                            <?= htmlspecialchars(mb_substr($recruit['recruit_title'] ?? '', 0, 80)) ?>
                        </div>

                        <div class="recruit-detail">
                            <?= nl2br(htmlspecialchars($recruit['recruit_detail'] ?? '詳細なし')) ?>
                        </div>

                        <form action="recruit_room_number.php" method="post">
                            <input type="hidden" name="recruit_id" value="<?= htmlspecialchars($recruit['recruit_id'] ?? '') ?>">
                            <input type="submit" class="join-btn" value="参加">
                        </form>
                        <div style="clear:both"></div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- 掲示板（ポスト） -->
            <div class="post-style">
                <?php foreach ($posts as $post): ?>
                    <div class="post-row" style="margin-bottom:12px;">
                        <div class="post-thumb">
                            <?php if (!empty($post['post_image'])): ?>
                                <img src="<?= htmlspecialchars($post['post_image']) ?>" alt="post" style="width:100%;height:100%;object-fit:cover;">
                            <?php endif; ?>
                        </div>
                        <div class="post-desc">
                            <?= nl2br(htmlspecialchars(mb_substr($post['post_detail'] ?? '', 0, 260))) ?>
                            <div style="font-size:12px; color:#ddd; margin-top:8px;">
                                <?= htmlspecialchars($post['post_date'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>

    <!-- 右：ゲーム一覧 -->
    <aside class="sidebar-right">
        <h4>ゲーム一覧</h4>
        <ul class="game-list">
            <?php foreach ($games as $name => $link): ?>
                <li><a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($name) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

</div>
<script src="../javascript/index.js?v=<?php echo time(); ?>"></script>
</body>
</html>
