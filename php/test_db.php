<?php
const DB_HOST = 'mysql:host=mysql327.phy.lolipop.lan;dbname=LAA1682436-chlorine3214;charset=utf8';
const DB_USER = 'LAA1682436';
const DB_PASSWORD = 'Kuroru960';

try {
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    echo "<p style='color:green'>✅ 接続成功しました！</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ ERROR: Could not connect. " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "</p>";
}
?>
