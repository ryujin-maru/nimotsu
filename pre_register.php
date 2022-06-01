<?php
session_start();
$_SESSION['token'] = uniqid(bin2hex(random_bytes(13)),true);
$token = $_SESSION['token'];

header('X-FRAME-OPTIONS:SAMEORIGIN');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section>
        <div class="login-box">
            <h1>会員登録</h1><br>
            <form method="POST" action="https://nimotsu.refine-web.co.jp/nimotsu/pre_register_check.php">
                <p>メールアドレス</p>
                <input type="mail" name="mail">
                <br>
                <input type="hidden" name="token" value="<?=$token?>">
                <input type="submit" name="submit" value="確認">
            </form>
        </div>
    </section>
    
</body>
</html>