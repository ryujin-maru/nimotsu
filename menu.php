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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="menu">
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
    </section>
</body>
</html>