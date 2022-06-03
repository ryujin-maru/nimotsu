<?php
require_once 'GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();

$qrCodeUrl = $ga->getQRCodeGoogleUrl('nimotsu', $secret);

$oneCode = $ga->getCode($secret);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>認証</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h1>二段階認証</h1>
        <p>シークレットキー</p>
        <p><?=$secret?></p><br>
        <p>QRコード</p>
        <img src="<?=$qrCodeUrl?>"><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/sampleLogin.php">認証へすすむ</a>
        
</body>
</html>