<?php

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
    <link rel="stylesheet" href="benkyo.css">
    <link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet">

    <style>
        .banner{
            width: 100%;
            height: 100px;
            background: linear-gradient(180deg, #377B98, #8A6CBE);
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }
        .vertical-line {
            border-left: 2px solid black;
            height: auto;
            margin: 20px;
        }

         .vertical-line2 {
            border-left: 2px solid black;
            height: auto;
            margin: 20px;
            right: 30px;
        }

    </style>
</head>

<body>

    <!-- バナー -->
    <div class="banner">
        <img src="画像1.png" alt="画像1" style="position: absolute; left: 20px; top: 20px; width: 80px; height: auto;">
        <div style="position: absolute; left: 115px; top: 45px; font-size: 30px; color: aqua;">
            PikoPikoFriends
        </div>
        <i class="fas fa-bell fa-2x" style="position: relative; left: 1150px; top: 20px; color: white;"></i>
        <div style="position: relative; left: 1200px; top: 20px; font-size: 25px; color: red;">
            ログアウト
        </div>
        <i class="fas fa-circle fa-2x" style="position: relative; left: 1250px; top: 20px; color: white;"></i>
    </div>
    <!-- ここでバナーを閉じるのが重要 !!! -->

    <div class="box"><p>Apex Legends<br>攻略一覧</p></div>

    <img src="apex-legends.jpg" alt="Apex Legends"
         style="position: absolute; left: 8px; top: 110px; width: 300px; height: auto;">

    <img src="Apex-Legends-Germany.jpg" alt="Apex-Legends-Germany"
         style="position: absolute; left: 8px; top: 278px; width: 120px; height: 120px;">

    <div style="position: absolute; left: 40px; top: 400px; font-size: 30px; color: gray;">
        人気なAPEX記事
    </div>

    <a href="http://localhost/kouryaku.php">
        <div style="position: absolute; left: 20px; top: 450px; font-size: 20px; color: skyblue;">
            APEX全キャラクター特徴まとめ
        </div>
    </a>

    <a href="http://localhost/kouryaku2.php">
        <div style="position: absolute; left: 20px; top: 480px; font-size: 20px; color: skyblue;">
            今シーズンのマップまとめ
        </div>
    </a>

    <a href="http://localhost/kouryaku3.php">
        <div style="position: absolute; left: 20px; top: 530px; font-size: 20px; color: skyblue;">
            使用率からみる<br>最強キャラランキング
        </div>
    </a>

    <a href="http://localhost/kouryaku4.php">
        <div style="position: absolute; left: 20px; top: 600px; font-size: 20px; color: skyblue;">
            スパレジェ一覧と<br>人気ランキングまとめ
        </div>
    </a>

    <div class="vertical-line"
         style="position: absolute; left: 290px; top: 90px; height: 1000px;"></div>

    <div style="position: absolute; left: 340px; top: 120px; font-size: 40px; color: grey;">
        最新のAPEX記事
    </div>

    <a href="http://localhost/kouryaku5.php">
        <div class="box2">
            <p>APEXシーズン27アップデート！！<br>
               武器の調整やマップの変更点、キャラクターの調整一覧！！</p>

            <img src="Apex_S27_Feature-Image-4x3.avif" alt="ApexS27"
                 style="position: absolute; left: 700px; top: 20px; width: 180px; height: auto;">
        </div>
    </a>

    <a href="http://localhost/kouryaku6.php">
        <div class="box3">
            <p>Apexやってるやつ<br>
               ↑うおW</p>

            <img src="uow" alt="tyousyou"
                 style="position: absolute; left: 700px; top: 90px; width: 180px; height: auto;">
        </div>
    </a>

    <a href="http://localhost/kouryaku7.php">
        <div class="box4">
            <p>APEX新キャラ<br>
               </p>

            <img src="leviathan-apex-legends.jpg" alt="leviathan"
                 style="position: absolute; left: 700px; top: 160px; width: 180px; height: auto;">
        </div>
    </a>

     
        
</body>
</html>
