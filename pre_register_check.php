<?php
require_once 'userLogic.php';
session_start();
$err = [];
header('X-FRAME-OPTIONS:SAMEORIGIN');

if($_SESSION['token'] != $_POST['token']) {
    echo '不正なアクセスの可能性があります';
    exit();
}

if(empty($_POST)) {
    header('Location:pre_register.php');
    exit();
}

if(empty($_POST['mail'])) {
    $err[] = 'メールアドレスを入力してください';
}

$result = userLogic::userMail($_POST['mail']);
if(!$result) {
    $err[] = 'このメールアドレスは既に登録済みです';
}

if(!$err){
    $urltoken = hash('sha256',uniqid(rand(),1));
    $url = 'https://nimotsu.refine-web.co.jp/nimotsu/userRegister.php?urltoken=' . $urltoken;

    $data = userLogic::register($urltoken,$_POST['mail']);
    if($data) {

        mb_language('ja');
        mb_internal_encoding('UTF-8');
        $to = $_POST['mail'];
        $subject = '会員登録（仮）完了のお知らせ';
        $from = 'densan@ravir-web.co.jp';
        
        if(mb_send_mail($to,$subject,$url)){
            session_destroy();
            $message = 'メールをお送りしました。5分以内にメールに記載されたURLからご登録下さい。';
        }else{
            $err[] = 'メールの送信に失敗しました。';
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
    <title>仮登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section>
        <div class="login-box">
            <?php if(!$err) { ?>
            <p><?=$message?></p>
            <?php }else{ 
            echo '<div>';
            echo implode('<br>',$err);
            echo '</div>';
            }
            ?>
        </div>
    </section>
</body>
</html>