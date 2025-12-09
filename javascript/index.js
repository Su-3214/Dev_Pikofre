// JavaScriptコーディング
// ページが読み込まれたら実行する処理
document.addEventListener("DOMContentLoaded", function () {
    // 1. 共通パーツの生成
    createHeader();
    createFooter();

    // 2. 既存の機能の初期化
    initScrollObserver();
    initHeaderAnimation();
    initCustomNav();
});
/**
 * ヘッダーを生成してbodyの先頭に挿入する関数
 */
function createHeader() {
    // 現在のパスを取得
    const path = window.location.pathname;
    let rootPath = "./";

    // パス調整
    if (path.includes("/php/")) {
        rootPath = "../";
    }

    // ページ判定: welcome.php かどうか
    const isWelcomePage = path.includes("welcome.php");

    // <header>要素を作成
    const header = document.createElement("header");

    // 共通パーツ: ロゴとタイトル
    let headerContent = `
        <img src="${rootPath}images/pikofre_icon.png" alt="ロゴ" height="100" width="100">
        <h1>PikoPikoFriends</h1>
    `;

    // ナビゲーションボタン（ページによって切り替え）
    if (isWelcomePage) {
        // ログイン済み（welcome.php）の場合: ログアウトボタン
        headerContent += `
            <nav class="login_nav">
                <a href="logout.php" class="btn btn-danger ml-3">ログアウト</a>
            </nav>
        `;
    } else {
        // 未ログイン（index.htmlなど）の場合: ログインボタン
        headerContent += `
            <nav class="login_nav">
                <a href="${rootPath}php/login.php">ログイン</a>
            </nav>
        `;
    }

    // 共通のナビゲーションメニュー
    //いらないので削除しようと思います。
    /*headerContent += `
        <nav class="header_nav">
            <a href="${rootPath}index.html">トップ</a>
            <a href="${rootPath}forum.html">掲示板</a>
            <a href="${rootPath}php/gametop.php">ゲームページ</a>
        </nav>
    `;*/

    header.innerHTML = headerContent;

    // 作成したheaderをbodyの一番最初に追加
    document.body.prepend(header);
}

/**
 * フッターを生成してbodyの末尾に挿入する関数
 */
function createFooter() {
    // 現在のパスを取得
    const path = window.location.pathname;
    let rootPath = "./";

    // パス調整
    if (path.includes("/php/")) {
        rootPath = "../";
    }

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
    // データ定義
    const links = [
        { label: '攻略記事', bg: '#c0392b' },
        { label: '募集', bg: '#16a085' },
        { label: '掲示板', bg: '#8e44ad' },
    ];

    // コンテナ作成
    const container = document.createElement('div');
    Object.assign(container.style, {
        position: 'fixed',
        top: '50%',
        right: '0%',
        transform: 'translateY(-50%)',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
        height: '240px',
        gap: '0px',
        zIndex: '1000'
    });
    document.body.appendChild(container);

    // 縦線の追加
    const line = document.createElement('div');
    Object.assign(line.style, {
        position: 'fixed',
        top: '50%',
        right: '180px',
        transform: 'translateY(-50%)',
        width: '3px',
        height: '240px',
        backgroundColor: '#555',
        borderRadius: '2px',
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
            width: '180px',
            padding: '12px 0',
            textAlign: 'center',
            borderRadius: '8px',
            backgroundColor: '#333',
            color: '#fff',
            textDecoration: 'none',
            fontFamily: 'sans-serif',
            fontSize: '18px',
            transition: 'all 0.3s ease',
            position: 'relative'
        });

        // 矢印作成
        const arrow = document.createElement('span');
        arrow.textContent = '▶';
        Object.assign(arrow.style, {
            position: 'absolute',
            left: '-25px',
            top: '50%',
            transform: 'translateY(-50%)',
            opacity: '0',
            transition: 'opacity 0.3s ease',
            color: linkData.bg,
            fontSize: '18px'
        });
        link.appendChild(arrow);

        // イベントリスナー
        link.addEventListener('mouseover', function () {
            link.style.backgroundColor = linkData.bg;
            arrow.style.opacity = '1';
            line.style.backgroundColor = linkData.bg;
        });

        link.addEventListener('mouseout', function () {
            link.style.backgroundColor = '#333';
            arrow.style.opacity = '0';
            line.style.backgroundColor = '#555';
        });

        container.appendChild(link);
    });
}