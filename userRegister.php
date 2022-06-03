<?php
session_start();
require_once 'userLogic.php';
$err = [];

$_SESSION['token'] = uniqid(bin2hex(random_bytes(13)),true);
$token = $_SESSION['token'];

if(empty($_GET)) {
    header('Location:https://nimotsu.refine-web.co.jp/nimotsu/pre_register.php');
    exit();
}else{
    $urltoken = $_GET['urltoken'];
    if($urltoken == '') {
        $err[] = 'もう一度登録をやり直してください';
        userLogic::delete($urltoken);
    }else{
        $result = userLogic::check($urltoken);
        if($result == false) {
            $err[] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
            userLogic::delete($urltoken);
        }else{
            $mail = $result;
            $_SESSION['mail'] = $result;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h1>会員登録</h1>
        <?php if(!$err) { ?>
            <form method="POST" action="register-check.php">
                <p>メールアドレス</p>
                <p><?=$mail?></p>
                <p>名前</p>
                <input type="text" name="name">
                <p>パスワード</p>
                <input type="password" name="pass"><br>
                <input type="hidden" name="token" value="<?=$token?>">
                <input type="submit" name="sub" value="登録">
            </form>
        <?php 
        }else{
        echo '<div>';
        echo implode('<br>',$err);
        echo '</div>';
        echo '<a href="https://nimotsu.refine-web.co.jp/nimotsu/login.php">ログイン';
        echo '</a>';
        }
        ?>

    </div>
</body>
</html>