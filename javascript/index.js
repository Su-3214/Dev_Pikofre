// JavaScriptコーディング
// ページが読み込まれたら実行する処理
document.addEventListener("DOMContentLoaded", function () {
    // 0. CSSの読み込み
    loadCommonCss();

    // 1. 共通パーツの生成
    createHeader();
    createFooter();

    // 2. 既存の機能の初期化
    // initScrollObserver(); // スクロール検知を無効化
    initHeaderAnimation();
    initCustomNav();
});
/**
 * ヘッダーを生成してbodyの先頭に挿入する関数
 */
function createHeader() {
    // 現在のパスを取得
    const rootPath = getRootPath();


    // ページ判定: welcome.php かどうか
    const path = window.location.pathname;

    // <header>要素を作成
    const header = document.createElement("header");

    // 共通パーツ: ロゴとタイトル
    let headerContent = `
        <img src="${rootPath}images/pikofre_icon.png" alt="ロゴ" height="100" width="100">
        <h1>PikoPikoFriends</h1>
        <style>
            h1 {
                margin: 0 auto;
            }
        </style>
    `;

    // 以下のファイル以外ではログアウトボタンを表示
    const filename = window.location.pathname.split('/').pop();
    if (filename !== "login.php") {
        headerContent += `
        <nav class="login_nav">
                <a href="logout.php" class="btn btn-danger ml-3">ログアウト</a>
            </nav>
        `;
    }

    header.innerHTML = headerContent;

    // 作成したheaderをbodyの一番最初に追加
    document.body.prepend(header);
}

/**
 * フッターを生成してbodyの末尾に挿入する関数
 */
function createFooter() {
    // 現在のパスを取得
    const rootPath = getRootPath();


    // <footer>要素を作成
    const footer = document.createElement("footer");

    // フッターの中身をHTMLとして記述
    footer.innerHTML = `
        <nav class="footer_nav">
            <a href="${rootPath}index.html">トップ</a>
            <a href="${rootPath}forum.html">掲示板</a>
            <a href="${rootPath}php/gametop.php">ゲームページ</a>
        </nav>
        <p>copyright Chlorine 2025 </p>
    `;

    // 作成したfooterをbodyの一番最後に追加(appendChild)
    document.body.appendChild(footer);
}

/**
 * スクロール検知の初期化
 */
function initScrollObserver() {
    window.addEventListener("scroll", function () {
        const header = document.querySelector("header");
        if (!header) return;

        const scrollPosition = window.scrollY;
        if (scrollPosition > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });
}

/**
 * ヘッダーのアニメーション初期化
 */
function initHeaderAnimation() {
    const header = document.querySelector("header");
    if (!header) return;

    header.animate(
        {
            opacity: [0, 1],
            transform: ["translateY(-20px)", "translateY(0)"]
        },
        {
            duration: 500,
            easing: "ease-out",
            fill: "forwards"
        }
    );
}

/**
 * 右側のカスタムナビゲーションと縦線の初期化
 */
function initCustomNav() {
    //以下のファイルはナビゲーションを表示しない
    const filename = window.location.pathname.split('/').pop();
    if (filename === "home.php" || filename === "login.php") {
        return;
    }
    // データ定義
    const links = [
        { label: '攻略記事', bg: '#FFFFBB' },
        { label: '募集', bg: '#EEDDEE' },
        { label: '掲示板', bg: '#DDEEAA' },
    ];

    // コンテナ作成
    const container = document.createElement('div');
    Object.assign(container.style, {
        backgroundColor: '#f5f7fa', // 全体を薄いグレーで統一
        position: 'fixed',
        top: 'calc(50% + 60px)', // 縦線が120pxから始まるため、残り領域の中央（50% + 60px）に配置
        right: '0%',
        transform: 'translateY(-50%)',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center', // 垂直方向中央揃え
        // height: '240px', // 高さ指定は削除してコンテンツに合わせる
        gap: '0px',
        zIndex: '1000'
    });
    document.body.appendChild(container);

    // 縦線の追加
    const line = document.createElement('div');
    Object.assign(line.style, {
        backgroundColor: '#333',     // 指定通り黒（ダークグレー）に
        position: 'fixed',
        top: '120px',        // 画面上部から
        right: '220px',  // ボタン幅に合わせて調整 (180px -> 220px)
        // transform: 'translateY(-50%)', // 不要
        width: '5px',    // 少し太くする (3px -> 5px)
        height: '100vh', // 画面全体の高さ
        // backgroundColor: '#555', // 重複していたグレー設定を削除
        zIndex: '1000'
    });
    document.body.appendChild(line);

    // リンクボタンの生成
    links.forEach(function (linkData) {
        const link = document.createElement('a');
        link.textContent = linkData.label;
        link.href = "#";

        Object.assign(link.style, {
            display: 'block',
            width: '220px',
            padding: '50px 0', // パディングを拡大 (12px -> 20px)
            textAlign: 'center',
            borderRadius: '8px 0 0 8px', // 左側のみ角丸にすると線と馴染むかも (一旦元のまま8px) -> Design choice: keep 8px for now
            backgroundColor: '#333',    // 指定通り黒（ダークグレー）に
            color: '#ffffff',           // 文字色は白に
            textDecoration: 'none',
            fontFamily: 'sans-serif',
            fontSize: '24px',  // フォントサイズ拡大 (18px -> 24px)
            fontWeight: 'bold', // 太字にして見やすく
            transition: 'all 0.3s ease',
            position: 'relative',
            marginBottom: '50px', // ボタン間の隙間を少し空ける
            webkitTextStroke: '1px #000' // 通常時のアウトライン（黒）
        });

        // 矢印作成
        const arrow = document.createElement('span');
        arrow.textContent = '▶';
        Object.assign(arrow.style, {
            position: 'absolute',
            left: '-35px',     // 文字サイズに合わせて位置調整
            top: '50%',
            transform: 'translateY(-50%)',
            opacity: '0',
            transition: 'opacity 0.3s ease',
            color: linkData.bg,
            fontSize: '24px'   // 矢印も大きく
        });
        link.appendChild(arrow);

        // イベントリスナー
        link.addEventListener('mouseover', function () {
            link.style.backgroundColor = linkData.bg;
            link.style.color = '#000'; // ホバー時は文字を黒に
            link.style.webkitTextStroke = '1px #fff'; // ホバー時のアウトライン（白）
            arrow.style.opacity = '1';
            line.style.backgroundColor = linkData.bg;
        });

        link.addEventListener('mouseout', function () {
            link.style.backgroundColor = '#333';    // 黒に戻す
            link.style.color = '#ffffff';           // 文字色を白に戻す
            link.style.webkitTextStroke = '1px #000'; // アウトラインを黒に戻す
            arrow.style.opacity = '0';
            line.style.backgroundColor = '#333';    // 黒に戻す
        });

        container.appendChild(link);
    });
}

/**
 * ルートパスを取得するヘルパー関数
 * @returns {string} "./" または "../"
 */
function getRootPath() {
    const path = window.location.pathname;
    // /php/ ディレクトリ内にいる場合は1つ上に戻る
    if (path.includes("/php/")) {
        return "../";
    }
    return "./";
}

/**
 * 共通CSS (style.css) を動的に読み込む関数
 */
function loadCommonCss() {
    const rootPath = getRootPath();
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = `${rootPath}css/style.css`;
    document.head.appendChild(link);
}
