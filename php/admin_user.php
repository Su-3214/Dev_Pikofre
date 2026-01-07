<?php

require_once "db_connect.php";
session_start();

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$users = [];

if ($search === '') {
  // 初期表示：全件取得
  $sql = "SELECT * FROM user ORDER BY u_date DESC";
  $stmt = $pdo->query($sql);
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // 検索時：IDは完全一致、名前は部分一致
  $sql = "SELECT * FROM user 
            WHERE u_id = :search1 
               OR u_name LIKE :search2 
            ORDER BY u_date DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':search1' => $search,          // 完全一致
    ':search2' => "%{$search}%"     // 部分一致
  ]);
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>




<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ユーザー管理（見た目のみ）</title>

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

    .side-menu ul {
      list-style: none;
      padding-left: 12px;
      margin: 0;
    }

    .side-menu ul li {
      margin: 6px 0;
    }

    .side-menu a {
      text-decoration: none;
      color: #000;
    }

    .side-menu a:hover {
      text-decoration: underline;
    }

    .logout {
      margin-top: 28px;
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
      font-size: 14px;
      font-weight: 600;
    }

    /* メイン */
    .main {
      flex: 1;
      padding: 20px 60px;
      box-sizing: border-box;
    }

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

    h1 {
      text-align: center;
      margin-top: 10px;
      font-size: 28px;
      font-weight: 700;
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

    table {
      margin: 20px auto;
      width: 80%;
      max-width: 800px;
      border-collapse: collapse;
      font-size: 18px;
    }

    th {
      padding: 12px 10px;
      border-bottom: 2px solid #999;
      font-weight: 600;
    }

    td {
      padding: 14px 10px;
      border-bottom: 1px solid #ccc;
    }

    .placeholder {
      color: #999;
      font-size: 18px;
      letter-spacing: 3px;
    }
  </style>

</head>

<body>

  <div class="layout">

    <!-- 左 -->
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

      <div class="logout" onclick="location.href='kanri_login.php'">
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


      <h1>ユーザー管理</h1>



      <div class="search-box">
        <form method="GET" action="">
          <input type="text" name="q"
            placeholder="ユーザー名またはユーザーIDを検索"
            value="<?= !empty($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
          <button type="submit">検索</button>
        </form>
      </div>




      <table>
        <tr>
          <th>ユーザー名</th>
          <th>ユーザーID</th>
          <th>登録日</th>
        </tr>

        <?php if (count($users) > 0): ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['u_name']) ?></td>
              <td><?= htmlspecialchars($u['u_id']) ?></td>
              <td><?= htmlspecialchars($u['u_date']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="placeholder">該当するユーザーが見つかりません</td>
          </tr>
        <?php endif; ?>


      </table>


    </main>

  </div>

    <script src="../javascript/index.js"></script>
</body>

</html>