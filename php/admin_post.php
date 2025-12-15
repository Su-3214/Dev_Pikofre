<?php
require_once "db_connect.php";
session_start();

global $pdo;

// 初期化
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$toukous = [];

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
  $delete_id = intval($_POST['post_id']);
  try {
    $sql_delete = "DELETE FROM piko_post WHERE post_id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$delete_id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } catch (PDOException $e) {
    error_log("削除エラー: " . $e->getMessage());
  }
}

// 投稿取得（通報関連なし）

$sql_toukou = "SELECT 
        p.post_id, p.post_date, p.post_detail, p.u_id,
        u.u_name
    FROM piko_post p
    LEFT JOIN user u ON p.u_id = u.u_id";

if ($search !== '') {
  $sql_toukou .= " WHERE u.u_name LIKE :search OR p.u_id = :search_id";
}

$sql_toukou .= " ORDER BY p.post_date DESC";

$stmt_toukou = $pdo->prepare($sql_toukou);

if ($search !== '') {
  $stmt_toukou->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
  $stmt_toukou->bindValue(':search_id', intval($search), PDO::PARAM_INT);
}

$stmt_toukou->execute();
$toukous = $stmt_toukou->fetchAll(PDO::FETCH_ASSOC);




?>



<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>投稿管理（見た目のみ）</title>

  <style>
    :root {
      --sidebar-width: 230px;
      --sidebar-bg: #a9d0ff;
      --accent: #0b6bd6;
    }

    body {
      margin: 0;
      font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', 'Yu Gothic', sans-serif;
    }

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* 左サイドバー */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--sidebar-bg);
      padding: 28px 20px;
      box-sizing: border-box;
      text-align: center;
    }

    .login-title {
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #e8edf3;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 8px auto 6px;
    }

    .avatar svg {
      width: 50px;
      height: 50px;
    }

    .username {
      font-size: 14px;
      margin-bottom: 14px;
    }

    .side-menu {
      text-align: left;
      margin-top: 10px;
      padding-left: 10px;
      font-size: 14px;
    }

    .side-menu .section-title {
      margin-bottom: 8px;
      font-weight: 600;
    }

    /* ← メニューをリンク化 */
    .side-menu a {
      color: #000;
      text-decoration: none;
      display: inline-block;
      width: 100%;
    }

    .side-menu a:hover {
      opacity: 0.7;
    }

    .side-menu ul {
      list-style: none;
      padding-left: 12px;
      margin: 0;
    }

    .side-menu ul li {
      margin: 6px 0;
    }

    /* ログアウト全体をリンク化 */
    .logout-link {
      text-decoration: none;
      color: inherit;
    }

    .logout {
      margin-top: 28px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
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
      font-size: 14px;
      font-weight: 600;
    }

    /* メイン */
    .main {
      flex: 1;
      padding: 20px 60px;
      box-sizing: border-box;
    }

    /* ロゴ */
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo svg {
      width: 40px;
      height: 40px;
    }

    .brand {
      font-size: 22px;
      font-weight: 700;
      color: var(--accent);
    }

    /* タイトル */
    h1 {
      text-align: center;
      margin-top: 10px;
      font-size: 28px;
      font-weight: 700;
    }


    .search-btn-wrap {
      text-align: center;
      margin-top: 10px;
    }

    .search-btn {
      width: 120px;
      padding: 10px 0;
      font-size: 17px;
      font-weight: 600;
      background: #1e88e5;
      color: white;
      border: none;
      cursor: pointer;
    }

    /* テーブル */
    table {
      margin: 30px auto;
      width: 80%;
      max-width: 900px;
      border-collapse: collapse;
      font-size: 18px;
      background: white;
    }

    th {
      padding: 12px 10px;
      border-bottom: 2px solid #999;
      font-weight: 600;
      text-align: left;
    }

    td {
      padding: 14px 10px;
      border-bottom: 1px solid #ccc;
      vertical-align: top;
    }

    .delete-btn {
      padding: 6px 16px;
      background: #ff5555;
      color: white;
      border: none;
      font-size: 15px;
      border-radius: 6px;
      cursor: pointer;
    }

    .placeholder {
      color: #999;
      font-size: 18px;
      letter-spacing: 3px;
    }

    .post-scroll-box {
      width: 80%;
      max-width: 900px;
      margin: 0 auto;
      height: 400px;
      overflow-y: auto;
      border: 1px solid #aaa;
      border-radius: 4px;
      display: flex;
      flex-direction: column;
    }

    .post-table {
      width: 100%;
      border-collapse: collapse;
      height: 100%;
    }

    .post-table tr:last-child td {
      border-bottom: none;
    }


    .no-post {
      text-align: center;
      vertical-align: middle;
      height: 100%;
      color: #666;
      font-size: 16px;
    }

    .piko-table td:nth-child(3),
    .piko-table th:nth-child(3) {
      width: 200px;
    }

    .piko-table td {
      white-space: nowrap;
      /* ← 改行を防ぐ */
    }

    .piko-table td:nth-child(3) {
      width: 240px;
      /* ← 投稿者列の幅を広げる（調整可） */
    }

    .search-box {
      margin: 24px auto 30px;
      width: 80%;
      max-width: 620px;
      display: flex;
    }

    .search-box form {
      display: flex;
      width: 99%;
      /* ← 横幅を広げる */
      max-width: 1000px;
      /* ← 最大幅を広げる */
    }

    .search-box input {
      flex: 1;
      font-size: 16px;
      padding: 10px 12px;
      border: 1px solid #aaa;
      border-right: none;
      outline: none;
    }

    .search-box button {
      width: 110px;
      background: #1e88e5;
      color: #fff;
      border: none;
      font-size: 17px;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <div class="layout">

    <!-- 左サイドバー -->
    <aside class="sidebar">
      <div class="login-title">管理者ログイン</div>

      <div class="avatar">
        <svg viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="3.5" stroke="#9aa7b6" stroke-width="1.2" fill="#f3f6f9" />
          <path d="M4 20c0-3.3 4-6 8-6s8 2.7 8 6"
            stroke="#9aa7b6" stroke-width="1.2" fill="none" stroke-linecap="round" />
        </svg>
      </div>

      <div class="username">ユーザー</div>

      <div class="side-menu">
        <div class="section-title">●ダッシュボード</div>
        <ul>
          <li><a href="admin_user.php">・ユーザー管理</a></li>
          <li><a href="admin_post.php">・投稿管理</a></li>
          <li><a href="admin_report_List.php">・通報リスト</a></li>
          <li><a href="admin_request.php">・修正リクエスト閲覧</a></li>
        </ul>
      </div>

      <a class="logout-link" href="kanri_login.php">
        <div class="logout">
          <svg viewBox="0 0 24 24">
            <path d="M9 7H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4"
              stroke="#111" stroke-width="1.6" fill="none" stroke-linecap="round" />
            <path d="M16 12h-7"
              stroke="#111" stroke-width="1.6" stroke-linecap="round" />
            <path d="M13 9l3 3-3 3"
              stroke="#111" stroke-width="1.6" stroke-linecap="round" />
          </svg>
          <div class="logout-text">ログアウト</div>
        </div>
      </a>

    </aside>

    <!-- メイン -->
    <main class="main">

      <a href="admin_home.php" style="text-decoration:none; color:inherit;">
        <div class="logo">
          <svg viewBox="0 0 64 64">
            <rect x="4" y="8" width="56" height="48" rx="8" fill="#3aa0ff" />
            <circle cx="20" cy="24" r="6" fill="#fff" />
            <path d="M36 26c0 6-8 12-16 12"
              stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" />
          </svg>
          <div class="brand">PikoPikoFriends</div>
        </div>
      </a>


      <h1>投稿管理</h1>

      <!-- フィルター2段 -->
      <div class="search-box">
        <form method="GET" action="">
          <input type="text" name="q"
            placeholder="ユーザー名またはユーザーidを検索"
            value="<?= !empty($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
          <button type="submit">検索</button>
        </form>
      </div>

      <!-- テーブル -->
      <div class="post-scroll-box">
        <table class="piko-table">
          <tr>
            <th>投稿ID</th>
            <th>投稿日時</th>
            <th>投稿者</th>
            <th>投稿内容</th>
            <th>操作</th>
          </tr>

          <?php if (count($toukous) > 0): ?>
            <?php foreach ($toukous as $tk): ?>
              <tr>
                <td><?= htmlspecialchars($tk['post_id']) ?></td>
                <td><?= htmlspecialchars($tk['post_date']) ?></td>
                <td>
                  ユーザーID：<?= htmlspecialchars($tk['u_id']) ?><br>
                  ユーザー名：<?= htmlspecialchars($tk['u_name']) ?>
                </td>
                <td><?= nl2br(htmlspecialchars($tk['post_detail'])) ?></td>
                <td>
                  <form method="post">
                    <input type="hidden" name="post_id" value="<?= $tk['post_id'] ?>">
                    <button class="delete-btn" onclick="return confirm('削除しますか？')">削除</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td class="no-post" colspan="5">該当する情報が見つかりません</td>
            </tr>
          <?php endif; ?>
        </table>
      </div>



    </main>
  </div>

</body>

</html>