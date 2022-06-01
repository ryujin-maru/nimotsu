<?php
function getDb() {
    $dsn = 'mysql:dbname=selfphp;host=localhost;charset=utf8';
    $name = 'test';
    $pass = 'test';
    
    try {
        $pdo = new PDO($dsn,$name,$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch(PDOException $e) {
        die('エラーメッセージ：'.$e->getMessage());
    }
}






