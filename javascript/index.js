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
        header.classList.remove("scrolled");
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
//================CustomNav================
//Datas
var links = [
    { label: '攻略記事', bg: '#c0392b' },
    { label: '募集', bg: '#16a085' },
    { label: '掲示板', bg: '#8e44ad' },
]
//AddContainer
var container = document.createElement('div');
container.style.position = 'fixed';
container.style.top = '50%';
container.style.right = '20%';
container.style.transform = 'transY(-50%)';
container.style.display = 'flex';
container.style.flexDirection = 'column';
container.style.justifyContent = 'space-between';
container.style.height = '240px';
container.style.gap = '0px';
document.body.appendChild(container);

//AddLinks
links.forEach(function (linkData) {
    var link = document.createElement('a');
    link.textContent = linkData.label;
    link.href = "#";
    link.dataset.color = linkData.bg;
    //Styles
    link.style.display = 'block';
    link.style.width = '180px';
    link.style.padding = '12px 0';
    link.style.textAlign = 'center';
    link.style.borderRadius = '8px';
    link.style.backgroundColor = '#333';
    link.style.color = '#fff';
    link.style.textDecoration = 'none';
    link.style.fontFamily = 'sans-serif';
    link.style.fontSize = '18px';
    link.style.transition = 'all 0.3s ease';

    //MouseOver
    link.addEventListener('mouseover', function () {
        link.style.backgroundColor = linkData.bg;
        document.body.style.backgroundColor = linkData.bg;
    });
    link.addEventListener('mouseout', function () {
        link.style.backgroundColor = '#333';
        document.body.style.backgroundColor = '#111';
    });

    container.appendChild(link);
});
// Default BackgroundColor  
document.body.style.backgroundColor = '#111';
document.body.style.margin = '0';
document.body.style.height = '100vh';
document.body.style.display = 'flex';
document.body.style.flexDirection = 'column';
//================CustomNav================