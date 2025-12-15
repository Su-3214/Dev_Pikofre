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
    "Apex Legends" => "game_apex.php",
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ホーム画面</title>
    <style>
        :root {
            --left-width: 220px;
            --right-width: 260px;
            --header-h: 80px;
            --accent: #2b9afc;
            --bg: #f5f5f5;
            --sidebar-bg: #ffffff;
        }

        /* Reset */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            font-family: "Yu Gothic", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
            color: #222;
        }

        /* Header (gradient, logo left, bell + logout right) */
        header.site-header {
            height: var(--header-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: linear-gradient(90deg, #3aa6d0 0%, #7c5aa3 50%, #b86db6 100%);
            color: white;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.08);
        }

        .site-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .site-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px;
        }

        .site-title {
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 1px;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .icon-bell {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: rgba(0, 0, 0, 0.6);
        }

        .logout-link {
            color: #ff4242;
            font-weight: 700;
            text-decoration: none;
        }

        .avatar-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.12);
        }

        /* Main area (below header) */
        .content-wrap {
            display: flex;
            gap: 20px;
            padding: 18px;
            align-items: flex-start;
        }

        /* Left pickup */
        .left-pickup {
            width: var(--left-width);
            background: white;
            padding: 18px;
            border-right: 2px solid #eee;
            height: calc(100vh - var(--header-h) - 36px);
            overflow: auto;
        }

        .left-pickup h3 {
            margin: 0 0 12px 0;
            font-size: 28px;
            color: #666;
            font-weight: 600;
        }

        .pickup-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .pickup-list li {
            margin-bottom: 16px;
        }

        .pickup-list a {
            color: #0077cc;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.3;
        }

        /* Center (main content) */
        .main {
            flex: 1;
            max-width: 920px;
            min-width: 600px;
        }

        .main .container {
            width: 100%;
        }

        /* Latest article box */
        .latest-box {
            border: 4px solid #111;
            background: white;
            padding: 14px;
            margin-bottom: 20px;
        }

        .latest-box .title {
            font-size: 22px;
            color: #666;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .latest-body {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .latest-thumb {
            width: 120px;
            height: 72px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
        }

        .latest-text {
            font-weight: 700;
            line-height: 1.6;
            font-size: 18px;
        }

        /* Recruit card style (catmaster like) */
        .recruit-card {
            background: linear-gradient(#d0d0d0, #bfbfbf);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 18px;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.06);
        }

        .recruit-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .recruit-top .name {
            font-size: 28px;
            font-weight: 800;
            color: white;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }

        .recruit-top .icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #111;
        }

        .recruit-body {
            background: #2b2b2b;
            color: white;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .recruit-detail {
            background: #444;
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
        }

        .join-btn {
            display: inline-block;
            background: #ff00c8;
            color: white;
            padding: 8px 14px;
            border-radius: 18px;
            font-weight: 800;
            text-decoration: none;
            float: right;
        }

        /* Smaller post card (minecrafter) */
        .post-style {
            background: linear-gradient(#cfcfcf, #bfbfbf);
            border-radius: 20px;
            padding: 16px;
            margin-top: 14px;
        }

        .post-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .post-thumb {
            width: 86px;
            height: 86px;
            background: #222;
            border-radius: 8px;
            overflow: hidden;
        }

        .post-desc {
            background: #4b4b4b;
            color: white;
            padding: 18px;
            border-radius: 10px;
            width: 100%;
            font-size: 16px;
        }

        /* Right sidebar games */
        .sidebar-right {
            width: var(--right-width);
            background: white;
            padding: 22px;
            border-left: 2px solid #eee;
            height: calc(100vh - var(--header-h) - 36px);
            overflow: auto;
        }

        .sidebar-right h4 {
            margin: 0 0 12px 0;
            font-size: 20px;
            color: #666;
        }

        .game-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .game-list li {
            margin-bottom: 14px;
        }

        .game-list a {
            color: #0077cc;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
        }

        /* Footer spacing */
        footer {
            padding: 30px;
            text-align: center;
            color: #999;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .main {
                min-width: 420px;
            }

            .left-pickup {
                display: none;
            }
        }

        @media (max-width: 800px) {
            .content-wrap {
                flex-direction: column;
            }

            .left-pickup,
            .sidebar-right {
                width: 100%;
                height: auto;
                border: none;
            }

            .main {
                min-width: auto;
            }
        }
    </style>
</head>

<body>

    <header class="site-header">
        <div class="site-brand">
            <img src="/pic/pikologo.png" alt="PikoPikoFriends">
            <div class="site-title">PikoPikoFriends</div>
        </div>

        <div class="header-actions">
            <div class="icon-bell" title="通知">
                <!-- simple bell SVG -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 17H9a3 3 0 0 1-3-3V11a6 6 0 0 1 12 0v3a3 3 0 0 1-3 3z" stroke="#111" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 20h2" stroke="#111" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <a class="logout-link" href="logout.php">ログアウト</a>
            <div class="avatar-dot"></div>
        </div>
    </header>

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
                    <li><a href="kouryaku_detail.php?id=<?= htmlspecialchars($info['info_id']) ?>"><?= htmlspecialchars($title) ?></a></li>
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

                            <a class="join-btn" href="https://2301037.perma.jp/piko/php/recruit_room_number.php">参加</a>
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

    <footer>
        <nav>
            <a href="home.php">ホーム</a> |
            <a href="recruit_home.php">募集</a> |
            <a href="post_home.php">掲示板</a> |
            <a href="kouryaku.php">攻略</a>
        </nav>
        <p style="margin-top:8px;color:#999">copyright chlorine 2025</p>
    </footer>

    <script>
        // 必要ならJSをここに追加
    </script>
</body>

</html>