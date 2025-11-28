<?php

require_once "db_connect.php";
session_start(); 




$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$reports = [];

if ($search === '') {
    // 初期表示：全件取得
    $sql = "SELECT * FROM piko_report ORDER BY report_date DESC";
    $stmt = $pdo->query($sql);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // 検索時：ID一致で絞り込み
    $sql = "SELECT * FROM piko_report 
            WHERE reporter_id = :search1 
               OR reported_id = :search2 
            ORDER BY report_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':search1' => $search,
        ':search2' => $search
    ]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>



<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>通報リスト</title>

<style>
  :root{
    --sidebar-width:230px;
    --sidebar-bg:#a9d0ff;
    --accent:#0b6bd6;
  }

  body{
    margin:0;
    font-family:'Hiragino Kaku Gothic ProN','メイリオ','Yu Gothic',sans-serif;
  }

  .layout{
    display:flex;
    min-height:100vh;
  }

  /* 左サイドバー */
  .sidebar{
    width:var(--sidebar-width);
    background:var(--sidebar-bg);
    padding:28px 20px;
    box-sizing:border-box;
    text-align:center;
  }

  .login-title{
    font-size:15px;
    font-weight:700;
    margin-bottom:10px;
  }

  .avatar{
    width:80px;
    height:80px;
    border-radius:50%;
    background:#e8edf3;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:8px auto 6px;
  }
  .avatar svg{width:50px;height:50px;}

  .username{
    font-size:14px;
    margin-bottom:14px;
  }

  .side-menu{
    text-align:left;
    margin-top:10px;
    padding-left:10px;
    font-size:14px;
  }

  .side-menu .section-title{
    margin-bottom:8px;
    font-weight:600;
  }

  .side-menu a{
    color:#000;
    text-decoration:none;
    display:inline-block;
    width:100%;
  }
  .side-menu a:hover{
    opacity:0.7;
  }

  .side-menu ul{
    list-style:none;
    padding-left:12px;
    margin:0;
  }

  .side-menu ul li{
    margin:6px 0;
  }

  /* ★ ユーザー管理と完全一致のログアウトCSS */
  .logout{
    margin-top:28px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    cursor:pointer;
  }

  .logout svg{
    width:40px;
    height:40px;
    background:#fff;
    border-radius:6px;
    padding:6px;
    box-shadow:0 0 1px rgba(0,0,0,0.2);
  }

  .logout-text{
    color:#e53935;
    font-size:14px;
    font-weight:600;
  }

  /* メイン */
  .main{
    flex:1;
    padding:20px 60px;
    box-sizing:border-box;
  }

  /* ロゴ */
  .logo{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .logo svg{
    width:40px;height:40px;
  }
  .brand{
    font-size:22px;
    font-weight:700;
    color:var(--accent);
  }

  /* タイトル */
  h1{
    text-align:center;
    margin-top:10px;
    font-size:28px;
    font-weight:700;
  }

  /* 検索欄 */
  .search-box{
    margin:24px auto 30px;
    width:80%;
    max-width:620px;
    display:flex;
  }
.search-box form {
  display: flex;
  width: 99%;             /* ← 横幅を広げる */
  max-width: 1000px;      /* ← 最大幅を広げる */
}
  .search-box input{
    flex:1;
    font-size:16px;
    padding:10px 12px;
    border:1px solid #aaa;
    border-right:none;
    outline:none;
  }

  .search-box button{
    width:110px;
    background:#1e88e5;
    color:#fff;
    border:none;
    font-size:17px;
    cursor:pointer;
  }

  /* 通報情報フォーム */
  .report-box{
    margin:0 auto;
    width:80%;
    max-width:520px;
    font-size:16px;
  }

  .report-item{
    margin-bottom:18px;
  }

  .report-item label{
    display:block;
    margin-bottom:4px;
    font-weight:600;
  }

  .report-item input{
    width:100%;
    padding:8px 10px;
    font-size:15px;
    border:1px solid #999;
  }

  .report-textarea{
    display:flex;
    gap:10px;
    align-items:flex-start;
  }

  textarea{
    width:100%;
    height:80px;
    padding:8px;
    font-size:15px;
    border:1px solid #999;
  }

 .confirm-btn {
    padding: 8px 14px;
    background: #ddd;
    border: 1px solid #777;
    font-size: 14px;
    cursor: pointer;

    /* 横書きに強制 */
    writing-mode: horizontal-tb !important;
    white-space: nowrap;}
.scroll-box {
  width: 80%;
  max-width: 620px;
  margin: 0 auto;
  height: 400px;            /* ← 高さを固定 */
  overflow-y: auto;
  border: 1px solid #aaa;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
  height: 100%;             /* ← 枠に合わせて高さを埋める */
}

.no-report {
  text-align: center;
  vertical-align: middle;
  height: 100%;             /* ← 高さを埋めて中央に配置 */
  color: #666;
  font-size: 16px;
}




</style>
</head>
<body>

<div class="layout">

  <!-- 左メニュー -->
  <aside class="sidebar">
    <div class="login-title">管理者ログイン</div>

    <div class="avatar">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="8" r="3.5" stroke="#9aa7b6" stroke-width="1.2" fill="#f3f6f9"/>
        <path d="M4 20c0-3.3 4-6 8-6s8 2.7 8 6"
          stroke="#9aa7b6" stroke-width="1.2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>

    <div class="username">ユーザー</div>

    <div class="side-menu">
      <div class="section-title">●ダッシュボード</div>
      <ul>
        <li><a href="kanri_user.php">・ユーザー管理</a></li>
        <li><a href="kanri_toukou.php">・投稿管理</a></li>
        <li><a href="kanri_tuhouList.php">・通報リスト</a></li>
        <li><a href="kanri_syuseiRequest.php">・修正リクエスト閲覧</a></li>
      </ul>
    </div>

    <!-- ★ここを統一版に変更 -->
    <div class="logout" onclick="location.href='kanri_login.php'">
      <svg viewBox="0 0 24 24">
        <path d="M9 7H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4"
          stroke="#111" stroke-width="1.6" fill="none" stroke-linecap="round"/>
        <path d="M16 12h-7"
          stroke="#111" stroke-width="1.6" stroke-linecap="round"/>
        <path d="M13 9l3 3-3 3"
          stroke="#111" stroke-width="1.6" stroke-linecap="round"/>
      </svg>
      <div class="logout-text">ログアウト</div>
    </div>

  </aside>

  <main class="main">

    <a href="kanri_home.php" style="text-decoration:none; color:inherit;">
      <div class="logo">
        <svg viewBox="0 0 64 64">
          <rect x="4" y="8" width="56" height="48" rx="8" fill="#3aa0ff"/>
          <circle cx="20" cy="24" r="6" fill="#fff"/>
          <path d="M36 26c0 6-8 12-16 12"
            stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
        <div class="brand">PikoPikoFriends</div>
      </div>
    </a>

    <h1>通報リスト</h1>


<!-- 検索欄 -->
<div class="search-box">
  <form method="GET" action="">
    <input type="text" name="q" 
           placeholder="通報者IDまたは被通報者IDを検索"
           value="<?= !empty($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    <button type="submit">検索</button>
  </form>
</div>


<div class="scroll-box">
  <table class="report-table" style="width:100%; border-collapse:collapse; font-size:16px;">
    <thead>
      <tr style="background:#e0f0ff;">
        <th style="border:1px solid #aaa; padding:10px;">通報ID</th>
        <th style="border:1px solid #aaa; padding:10px;">通報者ID</th>
        <th style="border:1px solid #aaa; padding:10px;">被通報者ID</th>
        <th style="border:1px solid #aaa; padding:10px;">通報日</th>
        <th style="border:1px solid #aaa; padding:10px;">　　</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($reports)): ?>
        <?php foreach ($reports as $report): ?>
          <tr>
            <td style="border:1px solid #aaa; padding:10px;"><?= htmlspecialchars($report['report_id']) ?></td>
            <td style="border:1px solid #aaa; padding:10px;"><?= htmlspecialchars($report['reporter_id']) ?></td>
            <td style="border:1px solid #aaa; padding:10px;"><?= htmlspecialchars($report['reported_id']) ?></td>
            <td style="border:1px solid #aaa; padding:10px;"><?= htmlspecialchars($report['report_date']) ?></td>
            <td style="border:1px solid #aaa; padding:10px; text-align:center;">
              <form method="POST" action="kanri_tuhouTaiou.php" style="margin:0;">
                <input type="hidden" name="report_id" value="<?= htmlspecialchars($report['report_id']) ?>">
                <button type="submit" class="confirm-btn">確認</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="border:1px solid #aaa; padding:10px; text-align:center;">該当する通報はありません。</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
  </main>
</div>
</body></html>