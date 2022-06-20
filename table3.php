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
    header("Location:login.php");
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
    $img = userLogic::png($_SESSION['s']);
            if(unlink($img)) {
                var_dump('成功');
            }else{
                print('失敗');
            }
    $ques = logic::deleteUser($_SESSION['s']);
    if($ques){
        header('Location:table3.php');
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
        header('Location:table3.php');
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
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="head">
            <p><span>⚙</span>テーブル画面</p>
        </div>
    </header>

    <div class="flex-flex y">
        <div class="tmp">
            <ul>
                <a href="menu.php"><li><i class="fa-solid fa-house-chimney"></i>TOP</li></a>
                <div class="li"><li><i class="fa-solid fa-user"></i>荷物登録画面<i class="fa-solid fa-angle-down"></i><i class="fa-solid fa-angle-up"></i></li>
                <div class="list">
                    <a href="register2.php"><p><i class="fa-solid fa-caret-right"></i>new 荷物登録画面</p></a>
                    <a href="register.php"><p><i class="fa-solid fa-caret-right"></i>old 荷物登録画面</p></a>
                </div>
                </div>
                <a href="table3.php"><li><i class="fa-solid fa-user"></i>テーブル一覧画面</li></a>
                <a href="second.php"><li><i class="fa-solid fa-user"></i>二段階認証設定</li></a>
                <a href="login.php"><li><i class="fa-solid fa-arrow-left"></i>ログアウト</li></a>
            </ul>
        </div>



        <div class="main-right">
            <div class="top1">
                <p><i class="fa-solid fa-table"></i>TABLE</p>
            </div>

            <div class="top2">
                <form class="form1" method="POST">
                <div class="input-flex">
                    <input type="text" name="input1" placeholder="名前検索">
                    <input class="input2" type="number" name="input2" style="width:10%;" placeholder="ID">
                    <p style="margin: 0 10px;">~</p>
                    <input class="input3" type="number" name="input3" style="width:10%;" placeholder="ID">

                    <div class="search1">
                    <input type="submit" value="検索" name="search">
                </div>
                </div>
                </form>
                <div class="ioi">
                    <a class="dl" href="csv.php"><i class="fa-solid fa-check"></i>ダウンロード</a>

                    <button class="csv">csvファイルで登録</button>
                </div>

            <div class="scroll">
            <table class="main-table">
            <thead>
                <tr>
                    <th width="120px">管理番号</th>
                    <th width="200px">名前</th>
                    <th width="120px">荷物の数</th>
                    <th width="200px">画像</th>
                    <th></th>
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
                        <td class="png">

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
                        <td><div class="ko"><form method="POST"><input type="hidden" name="r[]" value="<?=$row['id']?>"><input type="hidden" name="r[]" value="<?=$row['name']?>"><input type="hidden" name="r[]" value="<?=$row['number']?>"><input style="margin-right:15px;" type="submit" value="変更" name="up-button"></form><form method="POST"><input type="hidden" name="s" value="<?=$row['id']?>"><input type="submit" name="delete" value="削除"></form></div></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        </div>

        </div>

    </div>

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

    <div class="display3">
        <i class="fa-solid fa-xmark"></i>
        <form method="POST" class="form3 fo" action="register-confirm.php" enctype="multipart/form-data">
            <br>
            <h3>csvファイルで登録</h3><br>
            <p class="ii">ファイルを選択してください。</p>
            <input type="file" name="csv" accept="text/csv"><br>
            <input type="submit" name="up" value="アップロード">
        </form>

    </div>

<script src="sub.js"></script>
</body>
</html>