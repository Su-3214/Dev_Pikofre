<?php
    //データーベースの接続情報を格納
    const DB_HOST = 'mysql:dbname=user_login;host=mysql320.phy.lolipop.lan;charset=utf8';
    const DB_USER = 'LAA1553857';
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