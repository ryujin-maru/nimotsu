<?php
session_start();
require_once 'connect.php';

// if(isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
//     echo '不正アクセスの可能性があります';
//     exit();
// }

if(!$_SESSION['login']) {
    header("Location:login.php");
    exit;
}

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
                <div class="qr1">
                    <p>QRコード</p><br>
                    <img src="<?= $qr['qr'] ?>"><br><br>
                    <a style="margin-left: 0;" class="a" href="sampleLogin.php">認証へすすむ</a>
                </div>
            </div>





        </div>
    </div>


    <!-- <div class="login-box">
        <p>QRコード</p><br>
        <img src="<?= $qr['qr'] ?>"><br><br>
        <a href="sampleLogin.php">認証へすすむ</a>
    </div> -->
<script src="sub.js"></script>
</body>
</html>