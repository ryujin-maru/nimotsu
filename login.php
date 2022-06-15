<?php
require_once 'connect.php';
require_once 'userLogic.php';
session_start();
$err = [];
$_SESSION['login'] = '';

openssl_get_cipher_methods(); 

if($_SESSION['login']){
    session_destroy();
}

// ログインボタン押したときの処理
if(isset($_POST['login-submit'])) {

    // ログインIDが空もしくは11字以上の時
    if(!$_POST['login'] or mb_strlen($_POST['login']) > 11) {
        $err[] = 'ログインIDを正しく入力してください';
    }else{
        $_POST['login'] = htmlspecialchars($_POST['login']);
    }

    // パスワードが空もしくは11字以上の時
    if(!$_POST['pass'] or mb_strlen($_POST['pass']) > 11) {
        $err[] = 'パスワードを正しく入力してください';
    }else{
        $_POST['pass'] = htmlspecialchars($_POST['pass']);
    }

    // userデータベースに接続して、値があればメニュー画面へ飛ばす
    if(!$err) {
        try{
            $sql = 'SELECT * FROM free WHERE name=?';
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,$_POST['login']);
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$rows) {
            }else{
                $rows = password_verify($_POST['pass'],$rows['pass']);
            }

        }catch(PDOException $e){

        }

        if(!$rows){
            $err[] = 'ログインに失敗しました';
        }else{
            $result = userLogic::flag($_POST['login']);
            $_SESSION['login'] = true;
            $_SESSION['user'] = $_POST['login'];
            // header('Location:https://nimotsu.refine-web.co.jp/nimotsu/menu.php');
            //     exit();
            if(!$result){
                header("Location:https://nimotsu.refine-web.co.jp/nimotsu/sampleLogin.php");
                exit();
            }else{
                header('Location:menu.php');
                exit();
            }
        }
    
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="head">
            <p><span>⚙</span>Login</p>
        </div>
    </header>
    <section>

        <div class="login-box">
            <div class="front">
                <p>ログイン</p>
            </div>
            <?php 
            if($err) {
                echo '<div style="color: red; text-align: center">';
                echo implode('<br>',$err);
                echo '</div>';
            }
            ?>
            <form method="POST" class="form">
                <p>ユーザーID</p>
                <input type="text" name="login">
                <p>パスワード</p>
                <input type="text" name="pass">
                <br>
                <div class="inIn">
                    <input type="submit" name="login-submit" value="ログイン">
                </div>
            </form>
            <div style="text-align: center; margin-bottom:10px;">
                <a href="pre_register.php">新規ログイン</a>
            </div>
        </div>
    </section>
</body>
</html>