<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>管理者ホーム画面（見た目のみ）</title>

<style>
  :root{
    --sidebar-width:230px;
    --sidebar-bg:#a9d0ff;
  }

  body{
    margin:0;
    font-family:'Hiragino Kaku Gothic ProN','メイリオ','Yu Gothic',sans-serif;
    background:#fff;
    color:#111;
  }

  .layout{
    display:flex;
    min-height:100vh;
  }

  /* 左サイドバー */
  .sidebar{
    width:var(--sidebar-width);
    background:var(--sidebar-bg);
    padding:30px 20px;
    text-align:center;
    box-sizing:border-box;
  }

  .login-title{
    font-weight:700;
    font: size 15px;
    margin-bottom:15px;
  }

  .avatar{
    width:80px;
    height:80px;
    border-radius:50%;
    background:#e8edf3;
    margin:0 auto 10px;
    display:flex;
    align-items:center;
    justify-content:center;
  }

  .avatar svg{
    width:50px;
    height:50px;
  }

  .username{
    font-size:14px;
  }

  /* ログアウトボタン */
  .logout{
    margin-top:30px;
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
    font-size:15px;
    font-weight:600;
  }

  a{
    text-decoration:none;
    color:inherit;
  }

  /* メイン */
  .main{
    flex:1;
    padding:20px 60px;
    box-sizing:border-box;
    text-align:center;
  }

  .logo{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    margin-top:10px;
  }

  .logo svg{
    width:42px;
    height:42px;
  }

  .brand{
    font-size:22px;
    font-weight:700;
    color:#0b6bd6;
  }

  h1{
    margin-top:10px;
    font-size:32px;
    font-weight:800;
  }

  .menu{
    width:80%;
    max-width:760px;
    margin:20px auto 0;
    padding:0;
    list-style:none;
  }

  .menu li{
    font-size:28px;
    padding:20px 0;
    display:flex;
    align-items:center;
    gap:18px;
    cursor:pointer;
  }

  .menu li::before{
    content:"";
    width:26px;
    height:26px;
    background:#000;
    border-radius:50%;
    flex-shrink:0;
  }
</style>

</head>
<body>

<div class="layout">

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

    <!-- ログアウトリンク -->
    <a href="kanri_login.php">
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


    <h1>管理者ホーム画面</h1>

    <ul class="menu">
      <li onclick="location.href='kanri_user.php'">ユーザー管理</li>
      <li onclick="location.href='kanri_toukou.php'">投稿管理</li>
      <li onclick="location.href='kanri_tuhouList.php'">通報リスト</li>
      <li onclick="location.href='kanri_syuseiRequest.php'">修正リクエスト閲覧</li>
    </ul>
  </main>

</div>

</body>
</html>
