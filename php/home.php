<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db_connect.php";

$_SESSION['u_id'] = 1;
$_SESSION['game_id'] = 50000;

// 攻略記事
$sql_info = "SELECT * FROM game_info ORDER BY update_date LIMIT 3";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->execute();
$infos = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

// 募集
$sql_recruit = "SELECT * FROM game_recruitment ORDER BY recruit_start LIMIT 3";
$stmt_recruit = $pdo->prepare($sql_recruit);
$stmt_recruit->execute();
$recruits = $stmt_recruit->fetchAll(PDO::FETCH_ASSOC);

// 掲示板
$sql_post = "SELECT * FROM piko_post ORDER BY post_date LIMIT 3";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute();
$posts = $stmt_post->fetchAll(PDO::FETCH_ASSOC);

// ▼ ゲーム一覧（右サイドバー用）
$games = [
    "Apex Legends" => "game_apex.php",
    "Minecraft" => "game_minecraft.php",
    "Valorant" => "game_valorant.php",
    "Overwatch2" => "game_overwatch2.php",
    "スプラトゥーン3" => "game_splatoon3.php",
    "ポケモンSV" => "game_pokemon_sv.php",
    "Shadowverse WB" => "game_sv.php",
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>

    <style>
        :root {
            --sidebar-width: 230px;
            --sidebar-bg: #a9d0ff;
        }

        body {
            background: #f5f5f5;
            margin: 0;
            font-family: 'Yu Gothic', sans-serif;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* 左サイドバー */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            padding: 30px 20px;
            text-align: center;
            box-sizing: border-box;
            flex-shrink: 0;
        }

        .login-title {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e8edf3;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar svg {
            width: 50px;
            height: 50px;
        }

        .username {
            font-size: 14px;
        }

        .logout {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .logout svg {
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 6px;
            padding: 6px;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.2);
        }

        .logout-text {
            color: #e53935;
            font-size: 15px;
            font-weight: 600;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* メインコンテンツ */
        .main-content {
            flex: 1;
            margin: 0 20px;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
        }

        /* 最新記事 */
        .section-title {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .article-card {
            background: white;
            border: 2px solid black;
            padding: 15px;
        }

        .article-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .article-thumb {
            width: 110px;
            height: 70px;
            background: #000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 13px;
        }

        .article-text {
            font-size: 14px;
            line-height: 1.4;
        }

        /* 募集 */
        .recruit-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
        }

        .recruit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recruit-title {
            font-size: 22px;
            font-weight: bold;
        }

        .recruit-game-icon {
            width: 50px;
            height: 50px;
            background: gray;
            border-radius: 10px;
        }

        .recruit-body {
            margin-top: 10px;
            background: #000;
            color: white;
            padding: 10px;
            border-radius: 10px;
        }

        .join-btn {
            display: block;
            width: 100%;
            margin-top: 15px;
            background: #e100ff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        /* 掲示板 */
        .post-card {
            background: white;
            margin-top: 30px;
            padding: 15px;
            border-radius: 20px;
        }

        .post-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .post-item:last-child {
            border-bottom: none;
        }

        .post-thumb {
            width: 90px;
            height: 80px;
            background: #000;
            border-radius: 10px;
            margin-right: 15px;
        }

        .post-text {
            font-size: 14px;
        }

        /* ▼右サイドバー追加 */
        .sidebar-right {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            padding: 30px 20px;
            text-align: left;
            box-sizing: border-box;
            flex-shrink: 0;

            position: sticky;
            top: 0;
            align-self: flex-start;
            height: 100vh;
            overflow-y: auto;
            border-left: 2px solid #8bbef5;
        }

        .sb-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sb-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sb-list li {
            margin-bottom: 10px;
        }

        .sb-list a {
            color: #003366;
            font-size: 14px;
            text-decoration: none;
        }

        .sb-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="layout">

    <!-- ▼左サイドバー -->
    <aside class="sidebar">
        <div class="login-title">ユーザー</div>

        <div class="avatar">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="8" r="3.5" stroke="#9aa7b6" stroke-width="1.2" fill="#f3f6f9"/>
                <path d="M4 20c0-3.3 4-6 8-6s8 2.7 8 6" stroke="#9aa7b6" stroke-width="1.2" fill="none" stroke-linecap="round"/>
            </svg>
        </div>

        <div class="username">ゲスト</div>

        <a href="logout.php">
            <div class="logout">
                <svg viewBox="0 0 24 24">
                    <path d="M9 7H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4" stroke="#111" stroke-width="1.6" fill="none" stroke-linecap="round" />
                    <path d="M16 12h-7" stroke="#111" stroke-width="1.6" stroke-linecap="round" />
                    <path d="M13 9l3 3-3 3" stroke="#111" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                <div class="logout-text">ログアウト</div>
            </div>
        </a>
    </aside>

    <!-- ▼右サイドバー（追加された新機能） -->
    <aside class="sidebar-right">

        <h3 class="sb-title">新着攻略</h3>
        <ul class="sb-list">
            <?php foreach ($infos as $info): ?>
                <li>
                    <a href="kouryaku_detail.php?id=<?= $info['info_id'] ?>">
                        <?= htmlspecialchars($info['info_detail']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3 class="sb-title" style="margin-top:30px;">ゲーム一覧</h3>
        <ul class="sb-list">
            <?php foreach ($games as $name => $link): ?>
                <li><a href="<?= $link ?>"><?= htmlspecialchars($name) ?></a></li>
            <?php endforeach; ?>
        </ul>

    </aside>

    <!-- ▼メインコンテンツ -->
    <div class="main-content">
        <div class="container">

            <!-- 最新記事 -->
            <h2 class="section-title">最新の記事</h2>
            <div class="article-card">

                <?php foreach ($infos as $info): ?>
                    <div class="article-item">
                        <div class="article-thumb">サムネ</div>
                        <div class="article-text">
                            <?= htmlspecialchars($info['info_detail']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- 募集 -->
            <?php foreach ($recruits as $recruit): ?>
                <div class="recruit-card">
                    <div class="recruit-header">
                        <div>
                            <div class="recruit-title"><?= htmlspecialchars($recruit['u_name']) ?></div>
                            <div style="font-size:12px; color:#777;">残り<?= htmlspecialchars($recruit['recruit_number']) ?>人</div>
                        </div>
                        <div class="recruit-game-icon"></div>
                    </div>

                    <div class="recruit-body">
                        <?= nl2br(htmlspecialchars($recruit['recruit_detail'])) ?>
                    </div>

                    <a class="join-btn" href="https://2301037.perma.jp/piko/php/room_number.php">参加</a>
                </div>
            <?php endforeach; ?>

            <!-- 掲示板 -->
            <div class="post-card">

                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <div class="post-thumb"></div>
                        <div class="post-text">
                            <?= htmlspecialchars($post['post_detail']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>

</div>

<script src="javascript/index.js"></script>
</body>
</html>
