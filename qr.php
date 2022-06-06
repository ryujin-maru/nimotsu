<?php
session_start();
require_once 'connect.php';

$sql = 'SELECT qr from free WHERE name=?';
$db = getDb();
$stmt = $db->prepare($sql);
$stmt->bindValue(1,$_SESSION['user']);
$stmt->execute();
$qr = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <p>QRコード</p><br>
        <img src="<?= $qr['qr'] ?>"><br><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/sampleLogin.php">認証へすすむ</a>
    </div>
</body>
</html>