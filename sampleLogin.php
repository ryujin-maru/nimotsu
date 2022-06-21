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
    
    $result = userLogic::flag0($_SESSION['user']);

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
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="head">
            <p><span>⚙</span>二段階認証</p>
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
                <div class="nin2">
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
            </div>


        </div>



    </div>





    <!-- <div class="login-box">
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
    </div> -->
<script src="sub.js"></script>
</body>
</html>

