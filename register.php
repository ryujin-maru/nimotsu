<?php
require_once 'connect.php';
session_start();
$err = [];
$err2 = [];
$err3 = [];
$err4 = [];
$err5 = [];
$comp = '';
$comp2 = '';

if(isset($_POST['search'])) {
    $text_value = $_POST['search'];
}else{
    $text_value = '';
}

if(!$_SESSION['login']) {
    header("Location:https://nimotsu.refine-web.co.jp/nimotsu/login.php");
    exit;
}

if($_POST){
    $_SESSION['register'] = '';
}

// 登録ボタンを押したとき
if(isset($_POST['register'])) {

    // 名前が空もしくは11字以上のとき
    if(!$_POST['register_name'] or mb_strlen($_POST['register_name']) > 11){
        $err[] = '名前を正しく入力してください';
    }

    //荷物の数が空もしくは半角数字以外だったとき
    if(!$_POST['register_number'] or !ctype_digit(strval($_POST['register_number']))){
        $err[] = '荷物の数を正しく入力してください';
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
        $_SESSION['register'] = '登録完了！';
        header('Location:https://nimotsu.refine-web.co.jp/nimotsu/register.php');
        exit;
    }
}


// 検索ボタンを押したとき
if(isset($_POST['update-search'])) {
    if(!$_POST['search'] or !ctype_digit(strval($_POST['search']))) {
        $err2[] = '管理番号を正しく入力してください';
        $_SESSION['name'] = '';
        $_SESSION['number'] = '';
        $_SESSION['id'] = '';
    }

    // carryデータベースに接続して管理番号を検索する
    if(!$err2) {
        $sql = 'SELECT * FROM carry where id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,htmlspecialchars($_POST['search']));
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$rows){
            $err2[] = '管理番号が見つかりません';
        }else{
            $_SESSION['name'] = $rows['name'];
            $_SESSION['number'] = $rows['number'];
            $_SESSION['id'] = $_POST['search'];
            $db = null;
        }
    }
}

// 変更ボタンを押したとき
if(isset($_POST['confirm'])) {

    // 名前が空もしくは11字以上のとき
    if(!$_POST['update_name'] or mb_strlen($_POST['update_name']) > 11) {
        $err3[] = '名前を正しく入力してください';
    }

    //荷物の数が空もしくは半角数字以外だったとき
    if(!$_POST['update_number'] or !ctype_digit(strval($_POST['update_number']))) {
        $err3[] = '荷物の数を正しく入力してください';
    }

    // carryデータベースに接続して値を変更する
    if(!$err3) {
        $sql = 'UPDATE carry SET name=?,number=? WHERE id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,htmlspecialchars($_POST['update_name']));
        $stmt->bindValue(2,htmlspecialchars($_POST['update_number']));
        $stmt->bindValue(3,$_SESSION['id']);
        $stmt->execute();
        $_SESSION['name'] = '';
        $_SESSION['number'] = '';
        $_SESSION['id'] = '';
        $comp = '値の変更に成功しました！';
    }
}

// 削除ボタンを押したとき
if(isset($_POST['drop'])) {
    if(!$_POST['delete-search'] or !ctype_digit(strval($_POST['delete-search']))) {
        $err4[] = '管理番号を正しく入力してください';
    }

    // エラーがないとき管理番号が正しいか調べる
    if(!$err4) {
        $sql = 'SELECT * FROM carry WHERE id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,htmlspecialchars($_POST['delete-search']));
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);

        // 管理番号があった場合削除
        if(!$rows) {
            $err4[] = '管理番号が見つかりません';
        }else{
            $sql = 'DELETE FROM carry WHERE id=?';
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,htmlspecialchars($_POST['delete-search']));
            $stmt->execute();
            $db = null;
            $comp2 = '値の削除に成功しました！';
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
    <link rel="stylesheet" href="style.css">
    <title>荷物登録画面</title>
</head>
<body>
    <section class="register-back">
        <h1>荷物登録画面</h1>
        <h2>登録</h2>
        <?php
        // エラーを出力
        if($err){
            echo '<div style="color: red;">';
            echo implode('<br>',$err);
            echo '</div>';
        }
        // 登録完了の出力
        if(!isset($_SESSION['register'])){
            echo $_SESSION['register'] = '';
        }else{
            echo '<div style="color: red;">';
            echo $_SESSION['register'];
            echo '</div>';
        }
        ?>
        <form method="POST">
            <p>名前</p>
            <input type="text" name="register_name">
            <p>荷物の数</p>
            <input type="number" name="register_number" placeholder="半角数字のみ"><br>
            <input type="submit" value="登録" name="register">
        </form>

        <div class="update">
            <h2>変更</h2>
            <?php
            // エラーを出力
            if($err2){
                echo '<div style="color: red;">';
                echo implode('<br>',$err2);
                echo '</div>';
            }
            // エラーを出力
            if($err3){
                echo '<div style="color: red;">';
                echo implode('<br>',$err3);
                echo '</div>';
            }
            if($comp){
                echo '<div style="color: red;">';
                echo $comp;
                echo '</div>';
            }
            ?>
            <form method="POST">
                    <input type="number" name="search" placeholder="管理番号を入力" value="<?=$text_value?>">
                    <input type="submit" name="update-search" value="検索">
            </form>

            <form method="POST">
            <table border=1>
                <thead>
                    <tr>
                        <th >名前</th>
                        <th>荷物の数</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td><input type="text" name="update_name" value=<?php if(!isset($_POST['update-search'])){
                                echo '';
                                }else{ 
                                echo $_SESSION['name']; } ?>></td>

                            <td><input type="number" name="update_number" placeholder="半角数字のみ" value=<?php if(!isset($_POST['update-search'])){
                                echo '';
                                }else{ 
                                echo $_SESSION['number']; } ?>></td>
                        </tr>
                    </form>
                </tbody>
            </table>
            <input type="submit" value="変更" name="confirm">
        </form>
        </div>

        <div class="delete">
            <h2>削除</h2>
            <?php
            // エラーを出力
            if($err4) {
                echo '<div style="color: red;">';
                echo implode('<br>',$err4);
                echo '</div>';
            }
            if($comp2) {
                echo '<div style="color: red;">';
                echo $comp2;
                echo '</div>';
            }
            ?>
            <form method="POST">
                <input type="number" name="delete-search" placeholder="削除する管理番号を入力">
                <input type="submit" value="削除" name="drop">
            </form>
        </div>

        <br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/menu.php">メニュー画面へ</a><br><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/login.php">ログアウト</a>
    </section>
<script src="main.js"></script>
</body>
</html>