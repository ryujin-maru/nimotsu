<?php
session_start();
require_once 'connect.php';
$err = [];
$err1 = [];
$_SESSION['number'] = '';
$_SESSION['id'] = '';
$_SESSION['old'] = '';
$_SESSION['delete_id'] = '';

if(!$_SESSION['login']) {
    header("Location:https://nimotsu.refine-web.co.jp/nimotsu/login.php");
    exit;
}


if(!isset($_POST['register_name'])){
    $str = '';
}else{
    $str = $_POST['register_name'];
}

// 登録ボタンを押したとき
if(isset($_POST['register_sub'])) {
    // 名前が空もしくは11字以上のとき
    if(!$_POST['register_name'] or mb_strlen($_POST['register_name']) > 11){
        $err[] = '名前を正しく入力してください';
    }

    //荷物の数が空もしくは半角数字以外だったとき
    if(!$_POST['register_number']){
        $err[] = '荷物の数を正しく入力してください';
    }else{
        mb_convert_kana($_POST['register_number'],"n");
    }

    //carryデータベースに接続して登録する
    if(!$err && $_SERVER['REQUEST_METHOD']==='POST'){
        $sql = 'INSERT INTO carry(name,number) VALUE (?,?)';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,htmlspecialchars($_POST['register_name']));
        $stmt->bindValue(2,htmlspecialchars($_POST['register_number']));
        $stmt->execute();
        $db = null;
        $_SESSION['register'] = '登録が完了しました';
        $_SESSION['number'] = 1;
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php');
        exit();
    }
}else{
    $_SESSION['register'] = '';
}

if(isset($_POST['update_sub'])) {
    $sql = 'SELECT * FROM carry WHERE id=?';
    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1,htmlspecialchars($_POST['update_id']));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row){
        $_SESSION['id'] = '入力したidが見つかりません。';
        $_SESSION['number'] = 2;
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php');
        exit();
    }else{
        $_SESSION['old'] = $row;
        $sql = 'UPDATE carry SET name=?,number=? WHERE id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,htmlspecialchars($_POST['update1']));
        $stmt->bindValue(2,htmlspecialchars($_POST['update2']));
        $stmt->bindValue(3,htmlspecialchars($_POST['update_id']));
        $stmt->execute();
        $_SESSION['number'] = 2;
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php');
        exit();
    }
}

if(isset($_POST['delete_sub'])) {
    $sql = 'DELETE FROM carry WHERE id=?';
    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1,htmlspecialchars($_POST['delete_id']));
    $stmt->execute();
    $ctn = $stmt->rowCount();
    if($ctn === 0) {
        $_SESSION['delete_id'] = '削除するidが見つかりません。';
        $_SESSION['number'] = 3;
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php');
        exit();
    }else{
        $_SESSION['number'] = 3;
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php');
        exit();
    }
}

if(isset($_POST['s'])) {
    $s = intval($_POST['s']);
}else{
    $s = '';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="register-back">
        <h1>荷物登録画面</h1>
        <form>
            <input type="radio" name="select" value="登録">登録
            <input type="radio" name="select" value="変更">変更
            <input type="radio" name="select" value="削除" checked>削除
        </form>


            <div class="q fade">
                        <?php
                    // エラーを出力
                    if($err){
                        echo '<div style="color: red;">';
                        echo implode('<br>',$err);
                        echo '</div>';
                    }
                    ?>
                <form method="POST" action="">
                    <br>
                    <p>名前</p>
                    <input type="text" name="register_name" value="<?=$str?>">
                    <br><br>
                    <p>荷物の数</p>
                    <input type="number" name="register_number" placeholder="半角数字のみ">
                    <br><br>
                    <input type="submit" name="register_sub" value="登録" id="sbm">
                </form>
                    <br>
                <form method="POST" class="form3" action="https://nimotsu.refine-web.co.jp/nimotsu/register-confirm.php" enctype="multipart/form-data">
                    <h3>csvファイルで登録</h3><br>
                    <p class="ii">ファイルを選択してください。</p>
                    <input type="file" name="csv"><br>
                    <input type="submit" name="up" value="アップロード">
                </form>
            </div>

                <div class="q fade">
                    
                    <div class="err">
                        <p>idを正しく入力してください</p>
                        <P>名前を正しく入力してください</P>
                        <p>荷物の数を正しく入力してください</p>
                    </div>
                <form method="POST" id="userInfo">
                    <br>
                    <p>id</p>
                    <input type="number" name="update_id" id="update_id" placeholder="半角数字のみ" value="<?= $r ?>">
                    <p style="margin-top: 10px;">名前</p>
                    <input type="text" name="update1">
                    <p style="margin-top: 10px;">荷物の数</p>
                    <input type="number" name="update2" placeholder="半角数字のみ">
                    <br>
                    <input type="submit" name="update_sub" value="変更">
                </form>
            </div>

            <div class="q">
                <p class="yy" style="color: red;">idを正しく入力してください</p>
                <form method="POST" class="form1">
                    <p>id</p>
                    <input type="number" name="delete_id" placeholder="半角数字のみ" value="<?= $s ?>">
                    <input type="submit" name="delete_sub" value="削除">
                </form>
            </div>
            <a href="https://nimotsu.refine-web.co.jp/nimotsu/menu.php">メニュー画面へ</a>
    </section>
<script src="./main.js"></script>
</body>
</html>