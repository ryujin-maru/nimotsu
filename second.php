<?php
require_once 'GoogleAuthenticator.php';
require_once 'userLogic.php';
session_start();

if(!$_SESSION['login']) {
    header("Location:login.php");
    exit;
}

$token = uniqid(bin2hex(random_bytes(13)),true);


$result = userLogic::flag($_SESSION['user']);
if(!$result) {
    header('Location:lift.php');
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
        header('Location:qr.php');
        exit();
    }
}

$_SESSION['token'] = $token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二段階認証設定</title>
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="head">
            <p><span>⚙</span>二段階認証設定</p>
        </div>
    </header>

    <div class="flex-flex y">
        <div class="tmp">
            <ul>
                <a href="menu.php"><li><i class="fa-solid fa-house-chimney"></i>TOP</li></a>
                <div class="li"><li><i class="fa-solid fa-user"></i>荷物登録画面<i class="fa-solid fa-angle-down"></i><i class="fa-solid fa-angle-up"></i></li>
                <div class="list">
                    <a href="register2.php"><p><i class="fa-solid fa-caret-right"></i>new 荷物登録画面</p></a>
                    <a href="register.php"><p><i class="fa-solid fa-caret-right"></i>old 荷物登録画面</p></a>
                </div>
                </div>
                <a href="table3.php"><li><i class="fa-solid fa-user"></i>テーブル一覧画面</li></a>
                <a href="second.php"><li><i class="fa-solid fa-user"></i>二段階認証設定</li></a>
                <a href="login.php"><li><i class="fa-solid fa-arrow-left"></i>ログアウト</li></a>
            </ul>
        </div>

        <div class="main-right">
            <div class="top1">
                <p><i class="fa-solid fa-lock"></i>SECURITY</p>
            </div>

            <div class="top2">
                <div class="ij">
                    <form method="post" action="qr.php">
                    <p style="color: red; margin-bottom: 20px;">二段階認証を設定しますか？</p>
                    <input type="hidden" name="token" value="<?=$token?>">
                    <input type="submit" name="second" value="YES">
                    <button class="no"><a class="ini" href="menu.php">NO</a></button>
                </div>
                    </form>
            </div>


        </div>



    </div>
    <!-- <div class="login-box">
        <form method="post">
            <p style="color: red;">二段階認証を設定しますか？</p>
            <input type="submit" name="second" value="YES">

            <a href="menu.php"><input type="button" name="no" value="NO"></a>
        </form>
    </div> -->
<script src="sub.js"></script>
</body>
</html>