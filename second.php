<?php
require_once 'GoogleAuthenticator.php';
require_once 'userLogic.php';
session_start();

$result = userLogic::flag($_SESSION['user']);
if(!$result) {
    header('Location:https://nimotsu.refine-web.co.jp/nimotsu/lift.php');
    exit();
}else{
    $ga = new PHPGangsta_GoogleAuthenticator();
    $secret = $ga->createSecret();
    
    $qrCodeUrl = $ga->getQRCodeGoogleUrl('nimotsu', $secret);
    
    $oneCode = $ga->getCode($secret);
    
    if(isset($_POST['second'])) {
        userLogic::second($secret,$qrCodeUrl,$_SESSION['user']);
        $_SESSION['qr'] = $qrCodeUrl;
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/qr.php');
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二段階認証設定</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <form method="post">
            <p style="color: red;">二段階認証を設定しますか？</p>
            <input type="submit" name="second" value="YES">

            <a href="menu.php"><input type="button" name="no" value="NO"></a>
        </form>
    </div>
</body>
</html>