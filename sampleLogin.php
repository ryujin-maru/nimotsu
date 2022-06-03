<?php
session_start();

require_once 'GoogleAuthenticator.php';
require_once 'userLogic.php';

$err = '';

if(isset($_POST['send'])){
    $result = userLogic::key($_SESSION['user']);

    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($result['skey'], $_POST['gacode'], 2);
    if ($checkResult) {
    $_SESSION['login'] = true;
    header('Location:menu.php');
    exit();
    } else {
    $err = '認証に失敗しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h1>認証</h1>
        <p style="color: red;"><?=$err?></p>
    <form action="" method="post">
        <div>
            <p>６桁のコード</p>
            <input type="text" name="gacode">
        </div>
        <input type="hidden" name="token" value="<?=$token?>">
        <button type="submit" name="send" value="send">送信</button>
    </form>
    </div>
</body>
</html>

