<?php
require_once 'connect.php';
session_start();
$count = 0;

if(!$_SESSION['login']) {
    header("Location:https://nimotsu.refine-web.co.jp/nimotsu/login.php");
    exit;
}



// 確認ボタンを押したときnumberの合計を表示
if(isset($_POST['count'])) {
    $sql = 'SELECT SUM(number) from carry';
    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetch();
    $count = $rows[0];
    if(!$count){
        $count = 0;
    }
    $db = null;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー画面</title>
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="head">
            <p><span>⚙</span>メニュー画面</p>
        </div>
    </header>

    <div class="flex-flex">
        <div class="tmp">
            <ul>
                <a href="menu.php"><li><i class="fa-solid fa-house-chimney"></i>TOP</li></a>
                <div class="li"><li><i class="fa-solid fa-user"></i>荷物登録画面<i class="fa-solid fa-angle-down"></i><i class="fa-solid fa-angle-up"></i></li>
                <div class="list">
                    <a href="register2.php"><p><i class="fa-solid fa-caret-right"></i>new 荷物登録画面</p></a>
                    <a href="register.php"><p><i class="fa-solid fa-caret-right"></i>old 荷物登録画面</p></a>
                </div>
                </div>
                <a href="table2.php"><li><i class="fa-solid fa-user"></i>テーブル一覧画面</li></a>
                <a href="second.php"><li><i class="fa-solid fa-user"></i>二段階認証設定</li></a>
                <a href="login.php"><li><i class="fa-solid fa-arrow-left"></i>ログアウト</li></a>
            </ul>
        </div>



        <div class="main-right">
            <div class="top1">
                <p><i class="fa-solid fa-house-chimney"></i>MENU</p>
            </div>

            <div class="top2">
                <p class="let"><i class="fa-solid fa-comment"></i>荷物の数の合計を表示</p>

                <p style="margin-top: 40px; font-size: 25px;">現在の荷物の数は・・・</p>
                <p style="margin-top: 15px; font-size: 25px;"><span style="font-size: 25px; margin-right:10px;"><?= $count ?></span>個です。</p>

                <form method="POST">
                <input type="submit" name="count" value="確認">
                </form>
            </div>

        </div>

    </div>

    <!-- <section class="menu">
        <h1>メニュー画面</h1>
        <a class="a" href="https://nimotsu.refine-web.co.jp/nimotsu/table2.php">テーブル一覧画面へ</a><br><br>
        <a class="b" href="https://nimotsu.refine-web.co.jp/nimotsu/register.php">荷物登録画面へ</a><br><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/register2.php">荷物登録画面2へ</a><br><br>
        <form class="menu2" method="POST">
            <p class="kei0">荷物の合計を表示</p>
            <p ><span class="kei"><?= $count ?></span>個</p>
            <input type="submit" name="count" value="確認">
        </form>
        <br><a href="https://nimotsu.refine-web.co.jp/nimotsu/second.php">二段階認証設定</a><br><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/login.php">ログアウト</a>
    </section> -->
<script src="sub.js"></script>
</body>
</html>