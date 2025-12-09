<?php
require_once "db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $report_id = $_POST['report_id'];

    // 通報情報＋通報者名・被通報者名を取得
    $sql = "SELECT r.*, 
                   gr1.u_name AS reporter_name, 
                   gr2.u_name AS reported_name
            FROM piko_report r
            LEFT JOIN game_recruitment gr1 ON r.reporter_id = gr1.u_id
            LEFT JOIN game_recruitment gr2 ON r.reported_id = gr2.u_id
            WHERE r.report_id = :report_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':report_id' => $report_id]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        // ここで $report['reporter_name'], $report['reported_name'] も使える
    } else {
        echo "該当する通報が見つかりませんでした。";
    }
} else {
    echo "通報IDが渡されていません。";
}

?>





<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>通報対応（見た目のみ）</title>

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
.report-textarea {
  display: flex;
  flex-direction: row; /* 横並び */
  gap: 10px;
}

.report-textarea textarea {
  flex: 1; /* テキストエリアを広げる */
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

    <h1>通報対応</h1>




<!-- 通報情報フォーム（常時表示） -->
<div class="report-box">
  <div class="report-item">
    <label>通報ID</label>
    <input type="text" value="<?= $report ? htmlspecialchars($report['report_id']) : '' ?>">
  </div>

  <div class="report-item">
    <label>投稿ID</label>
    <input type="text" value="<?= $report ? htmlspecialchars($report['post_id']) : '' ?>">
  </div>

  <div class="report-item">
    <label>通報者ID</label>
    <input type="text" value="<?= $report ? htmlspecialchars($report['reporter_id']) : '' ?>">
  </div>
<div class="report-item">
  <label>通報者名</label>
  <input type="text" value="<?= htmlspecialchars($report['reporter_name']) ?>" readonly>
</div>
  
  <div class="report-item">
    <label>被通報者ID</label>
    <input type="text" value="<?= $report ? htmlspecialchars($report['reported_id']) : '' ?>">
  </div>
  
  <div class="report-item">
  <label>被通報者名</label>
  <input type="text" value="<?= htmlspecialchars($report['reported_name']) ?>" readonly>
</div>

  <div class="report-item">
    <label>通報内容</label>
    <div class="report-textarea">
      <textarea><?= $report ? htmlspecialchars($report['report_detail']) : '' ?></textarea>
     <form method="POST" action="kanri_toukou.php">
  <input type="hidden" name="from_report" value="1">
  <input type="hidden" name="reporter_id" value="<?= htmlspecialchars($report['reporter_id']) ?>">
  <input type="hidden" name="reported_id" value="<?= htmlspecialchars($report['reported_id']) ?>">

  <button type="submit">確認</button>
</form>


  <div class="report-item">
    <label>通報日時</label>
    <input type="text" value="<?= $report ? htmlspecialchars($report['report_date']) : '' ?>">
  </div>
</div>






  </main>

</div>

</body>
</html>
