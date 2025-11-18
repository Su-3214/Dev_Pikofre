<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>投稿管理（見た目のみ）</title>

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

  /* ← メニューをリンク化 */
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

  /* ログアウト全体をリンク化 */
  .logout-link{
    text-decoration:none;
    color:inherit;
  }

  .logout{
    margin-top:28px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
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

  /* 検索欄 上段2つ */
  .filter-row{
    margin:24px auto;
    width:80%;
    max-width:720px;
    display:flex;
    justify-content:space-between;
    gap:20px;
  }

  .filter-box{
    display:flex;
    flex-direction:column;
    width:48%;
    font-size:15px;
  }

  .filter-box input,
  .filter-box select{
    margin-top:6px;
    padding:10px 12px;
    font-size:16px;
    border:1px solid #aaa;
  }

  .search-btn-wrap{
    text-align:center;
    margin-top:10px;
  }

  .search-btn{
    width:120px;
    padding:10px 0;
    font-size:17px;
    font-weight:600;
    background:#1e88e5;
    color:white;
    border:none;
    cursor:pointer;
  }

  /* テーブル */
  table{
    margin:30px auto;
    width:80%;
    max-width:900px;
    border-collapse:collapse;
    font-size:18px;
    background:white;
  }

  th{
    padding:12px 10px;
    border-bottom:2px solid #999;
    font-weight:600;
    text-align:left;
  }

  td{
    padding:14px 10px;
    border-bottom:1px solid #ccc;
    vertical-align:top;
  }

  .delete-btn{
    padding:6px 16px;
    background:#ff5555;
    color:white;
    border:none;
    font-size:15px;
    border-radius:6px;
    cursor:pointer;
  }

  .placeholder{
    color:#999;
    font-size:18px;
    letter-spacing:3px;
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
        <li><a href="kanri_tuhouTaiou.php">・通報対応</a></li>
        <li><a href="kanri_syuseiRequest.php">・修正リクエスト閲覧</a></li>
      </ul>
    </div>

    <a class="logout-link" href="kanri_login.php">
      <div class="logout">
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
    </a>

  </aside>

  <!-- メイン -->
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


    <h1>投稿管理</h1>

    <!-- フィルター2段 -->
    <div class="filter-row">
      <div class="filter-box">
        ユーザー名またはid
        <input type="text" value="kids_1234">
      </div>

      <div class="filter-box">
        ステータス
        <select>
          <option>公開中</option>
          <option>非公開</option>
        </select>
      </div>
    </div>

    <div class="search-btn-wrap">
      <button class="search-btn">検索</button>
    </div>

    <!-- テーブル -->
    <table>
      <tr>
        <th>ID</th>
        <th>内容</th>
        <th>ステータス</th>
        <th></th>
      </tr>

      <tr>
        <td>kids_1234</td>
        <td>[minecraft]<br>PvP 1vs1募集</td>
        <td>公開中</td>
        <td><button class="delete-btn">削除</button></td>
      </tr>

      <!-- placeholder rows -->
      <tr><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder"></td></tr>
      <tr><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder"></td></tr>
      <tr><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder">ーーー</td><td class="placeholder"></td></tr>

    </table>

  </main>
</div>

</body>
</html>
