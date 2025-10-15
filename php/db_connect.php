<?php
    //データーベースの接続情報を格納
    //Suu DB Date↓
    const DB_HOST = 'mysql:host=mysql327.phy.lolipop.lan;dbname=LAA1682436-chlorine3214;charset=utf8';
    const DB_USER = 'LAA1682436';
    const DB_PASSWORD = 'Kuroru960';
    
    try{
        $pdo = new PDO(DB_HOST,DB_USER,DB_PASSWORD,[
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    } catch (PDOException $e){
        echo 'ERROR: Could not connect.' .$e->getMessage()."\n";
        exit();
    }
?>
