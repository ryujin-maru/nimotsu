<?php
require_once 'userLogic.php';
session_start();
$err = '';
$num = 0;

if($_SESSION['token'] != $_POST['token'] || !isset($_POST['token'])) {
    echo '不正アクセスの可能性があります。';
    exit();
}


if(isset($_POST['x'])){
    $_SESSION['y'] = $_POST['y'];
}

if(isset($_POST['submit'])) {
    $result = userLogic::img($_SESSION['y']);
    if($result == true){
        header('Location:table2.php');
        exit();
    }else{
        $num = 1;
        $err = '画像の挿入に失敗しました。';
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像の挿入</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h3>挿入する画像を選択してください</h3>
        <?php if($err){ ?>
        <p style="color: red;"><?=$err?></p>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="img" accept="image/png,image/jpeg">
            <input type="submit" name="submit" value="アップロード">
        </form>
        <br>
        <a href="table2.php">テーブル画面へ</a>
    </div>
</body>
</html>