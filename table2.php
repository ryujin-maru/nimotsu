<?php
require_once 'connect.php';
require_once 'logic.php';
require_once 'userLogic.php';
session_start();
$where1 = '=';
$like = '';
$a = "1";
$num = 0;

$_SESSION['token'] = uniqid(bin2hex(random_bytes(13)),true);
$token = $_SESSION['token'];


if(!$_SESSION['login']) {
    header("Location:https://nimotsu.refine-web.co.jp/nimotsu/login.php");
    exit;
}

if(isset($_POST['search'])) {

    
    if(!$_POST['input1']){
        $where1 = '!=';
    }else{
        $where1 = '';
        $like = 'LIKE';
    }
    if(!$_POST['input2']){
        $_POST['input2'] = 0;
    }
    if(!$_POST['input3']){
        $_POST['input3'] = 99999999999;
    }

    $sql = "SELECT * FROM carry WHERE name $like $where1 ? AND id BETWEEN ? AND ?";
    // $sql = "SELECT * FROM carry WHERE number BETWEEN ? AND ?";

    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1,'%'.htmlspecialchars($_POST['input1'].'%'));
    $stmt->bindValue(2,htmlspecialchars($_POST['input2']));
    $stmt->bindValue(3,htmlspecialchars($_POST['input3']));
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['csv'] = $result;


}else{
    $sql = "SELECT * FROM carry";
    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['csv'] = $result;
}

if(isset($_POST['delete'])) {
    $num = 1;
    $_SESSION['s'] = $_POST['s'];
}

if(isset($_POST['yes'])) {
    $ques = logic::deleteUser($_SESSION['s']);
    if($ques){
        header('Location:table2.php');
        exit();
    }
}

if(!isset($_POST['r'])) {
    $_POST['r'] = '';
}

if(isset($_POST['up-button'])) {
    $num = 2;
}

if(isset($_POST['update'])) {
    $post1 = intval($_POST['up-id']);
    $post2 = strval($_POST['up-name']);
    $post3 = intval($_POST['up-num']);
    var_dump($post1);
    $up = logic::updateUser($post1,$post2,$post3);
    if($up) {
        header('Location:table2.php');
    }else{
        print('失敗');
    }
}

// if(isset($_POST['upload'])) {
//     $r = intval($_POST['po']);
//     $result = userLogic::img($r);
//     header('Location:table2.php');
//     exit();
//     }
if(isset($_POST['yes2'])) {
    $delete = userLogic::delete_img($_SESSION['s']);
}

?>
<script>
    const j = JSON.parse('<?php echo $j;?>');
</script>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テーブル一覧画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="table-back">
        <h1 class="table-display">テーブル一覧画面</h1>
        <form class="form1" method="POST">
            <div class="input-flex">
                <input type="text" name="input1" placeholder="名前検索">
                <input class="input2" type="number" name="input2" style="width:10%;" placeholder="ID">
                <p>~</p>
                <input class="input3" type="number" name="input3" style="width:10%;" placeholder="ID">
            </div>
            <div class="search1">
                <input type="submit" value="絞り込み" name="search">
            </div>
        </form>
        <table class="main-table" border=1>
            <thead>
                <tr>
                    <th>管理番号</th>
                    <th>名前</th>
                    <th>荷物の数</th>
                    <th width="30%">画像</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <!-- レコードの数だけループを回してテーブルを表示 -->
                <?PHP
                foreach($result as $row) {
                    ?>
                    <tr>
                        <td><?= $row['id']?></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['number']?></td>
                        <td>

                        <?php
                        $png = userLogic::png($row['id']);
                        ?>

                        <?php if(!$png){ ?>    
                        <!-- <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="img"><br>
                            <input type="hidden" name="po" value="<?=$row['id']?>">
                            <input type="submit" name="upload" value="送信">
                        </form> -->
                        <?php }else{ ?>
                            <div class="img-center">
                                <img class="img" src="<?=$png?>">
                            </div>

                        <?php } ?>
                        </td>
                        <td><div class="ko"><form method="POST"><input type="hidden" name="r[]" value="<?=$row['id']?>"><input type="hidden" name="r[]" value="<?=$row['name']?>"><input type="hidden" name="r[]" value="<?=$row['number']?>"><input type="submit" value="変更" name="up-button"></form><form method="POST"><input type="hidden" name="s" value="<?=$row['id']?>"><input type="submit" name="delete" value="削除"></form></div></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/menu.php">メニュー画面へ</a><br><br>
        <a href="https://nimotsu.refine-web.co.jp/nimotsu/login.php">ログアウト</a><br><br>
        <a href="csv.php">csvダウンロード</a>
        <form method="POST" class="form3" action="register-confirm.php" enctype="multipart/form-data">
                    <br>
                    <h3>csvファイルで登録</h3><br>
                    <p class="ii">ファイルを選択してください。</p>
                    <input type="file" name="csv"><br>
                    <input type="submit" name="up" value="アップロード">
                </form>
    </section>

    <?php if($num == 1){ ?>
    <section>
        <div class="display">
            <p>管理番号<span style="color: red;"><?=$_POST['s']?></span>を削除しますか？</p>
            <div class="flex-btn">
                <form method="POST" id="yssNo">
                    <input type="submit" name="yes" value="はい">
                </form>
                <button class="no">いいえ</button>
            </div>
            <p>画像を削除しますか？</p>
            <div style="margin-top:0;" class="flex-btn">
                <form method="POST" id="yssNo">
                    <input type="submit" name="yes2" value="はい">
                </form>
                <button class="no">いいえ</button>
            </div>
        </div>
    </section>
    <?php }else if($num == 2) { ?>
        <section>
            <div class="display2">
                <form method=POST>
                    <p class="op">管理番号</p>
                    <input type="number" name="up-id" value="<?= $_POST['r'][0] ?>">
                    <p>名前</p>
                    <input type="text" name="up-name" value="<?= $_POST['r'][1] ?>">
                    <p>荷物の数</p>
                    <input type="number" name="up-num" value="<?= $_POST['r'][2] ?>"><br>
                    <div class="uu">
                        <input type="submit" name="update" value="変更">
                        <button type="button" class="oo">キャンセル</button>
                    </div>
                </form>
                <form method="POST" action="image.php">
                <input type="hidden" name="token" value="<?=$token?>">
                    <input type="hidden" name="y" value="<?=$_POST['r'][0]?>">
                    <input type="submit" name="x" value='画像挿入'>
                </form>
                
            </div>
        </section>
    <?php } ?>
<script src="sub.js"></script>
</body>
</html>