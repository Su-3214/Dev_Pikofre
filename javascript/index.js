//JavaScriptコーディング
//スクロールイベントを監視
window.addEventListener("scroll", function () {
    const header = document.querySelector("header");
    //↓現在位置を特定
    const scrollPosition = this.window.scrollY;
    //スクロール量に応じて画面変化
    if (scrollPosition > 50) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrollerd");
    }
});
//ヘッダーアニメーション
document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector("header");
    header.animate(
        //keyflame
        {
            opacity: [0, 1],
            transform: ["translateY(-20px)", "translateY(0)"]
        },
        //timing
        {
            duration: 500,//0.5s
            easing: "ease-out",
            fill: "forwards"
        }
    )
})