<?php
session_start();
$err = [];

if(isset($_POST['token'])){
    if($_SESSION['token'] != $_POST['token']) {
        echo '不正アクセスの可能性があります。';
    }
}

if(isset($_POST['submit'])) {
    if(is_uploaded_file($_FILES['img']['tmp_name'])) {
        $pathFile = $_FILES['img']['tmp_name'];
        $pathName = $_FILES['img']['name'];
    
        if(pathinfo($pathName,PATHINFO_EXTENSION) != 'png') {
            $err[] = 'img,png,jpgファイルのみアップロード可能です。';
        }else{
            $path = './img/';
            $myPath = $path.basename($pathName);

            move_uploaded_file($pathFile,$myPath);
            
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
    <title>画像の挿入</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h3>挿入する画像を選択してください</h3>
        <?php
        if($err){
            echo '<div style="color:red;">';
            echo implode('<br>',$err);
            echo '</div>';
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="img">
            <input type="submit" name="submit" value="アップロード">
        </form>
    </div>
</body>
</html>