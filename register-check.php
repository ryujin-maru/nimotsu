<?php
require_once 'userLogic.php';
session_start();

$err = [];
if($_SESSION['token'] != $_POST['token']) {
    print '不正アクセスの可能性があります';
    exit();
}

header('X-FRAME-OPTIONS:SAMEORIGIN');

if(isset($_POST['sub'])) {
    
    if(!$_POST['name'] || strlen($_POST['name']) > 10) {
        $err[] = '名前を正しく入力してください';
    }

    if(!$_POST['pass'] || strlen($_POST['pass']) > 10) {
        $err[] = 'パスワードを正しく入力してください';
    }

    if(!$err) {
        $hash = password_hash($_POST['pass'],PASSWORD_DEFAULT);

        $result = userLogic::complete($_POST['name'],$hash);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
    <?php if(!$err){ ?>
    <h1>登録完了</h1>
    <a href="https://nimotsu.refine-web.co.jp/nimotsu/login.php">ログイン画面へ</a>
    <?php }else
    echo '<div>';
    echo implode('<br>',$err);
    echo '</div>';
    ?>
    </div>
</body>
</html>