<?php
require_once 'userLogic.php';
session_start();
if(isset($_POST['yes'])) {
    $result = userLogic::lift($_SESSION['user']);
    
    if($result) {
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/login.php');
        exit();
    }else{
        print('失敗');
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二段階認証解除</title>
</head>
<body>
    <div class="login-box">
        <p>失敗しまし</p>
    </div>
</body>
</html> -->