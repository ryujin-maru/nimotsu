<?php
session_start();
require_once 'connect.php';
require_once 'userLogic.php';
$success = '';
$index = 0;
$err = [];
if(!$_SESSION['login']) {
    header("Location:login.php");
    exit;
}

if(isset($_POST['up'])) {
    if(is_uploaded_file($_FILES['csv']['tmp_name'])) {
        $filePath = $_FILES['csv']['tmp_name'];
        $fileName = $_FILES['csv']['name'];
    }

    if(pathinfo($fileName,PATHINFO_EXTENSION) != 'csv') {
        $err[] = 'csv拡張子のみアップロード可能です。';
        unlink($filePath);
    }else{
        $file = fopen($filePath,'r');

        while($data = fgetcsv($file)) {
            mb_convert_variables('UTF-8','SJIS',$data);
            if($index === 0) {
                $index++;
            }else{
                $sql = 'SELECT * FROM carry WHERE id=?';
                $db = getDb();
                $stmt = $db->prepare($sql);
                $stmt->bindValue(1,$data[0]);
                $stmt->execute();
                $cdn = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$cdn) {
                    $sql = 'INSERT INTO carry VALUE (?,?,?,?)';
                    $db = getDb();
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(1,$data[0]);
                    $stmt->bindValue(2,$data[1]);
                    $stmt->bindValue(3,$data[2]);
                    $stmt->bindValue(4,$data[3]);
                    $stmt->execute();
                    $index++;
                    $success = 'ファイルをインポートしました。';
                }else{
                    $sql = 'UPDATE carry SET name=?,number=?,img=? WHERE id=?';
                    $db = getDb();
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(1,htmlspecialchars($data[1]));
                    $stmt->bindValue(2,htmlspecialchars($data[2]));
                    $stmt->bindValue(3,htmlspecialchars($data[3]));
                    $stmt->bindValue(4,htmlspecialchars($data[0]));
                    $stmt->execute();
                    $cdn = $stmt->rowCount();
                    $success = 'ファイルをインポートしました。';
                }
                
            }
            }
        // $sql = "LOAD DATA LOCAL INFILE ? INTO TABLE carry CHARACTER SET sjis FIELDS TERMINATED BY ',' IGNORE 1 LINES (@1,@2,@3) SET name=@2,number=@3";
        // $db = getDb();
        // $stmt = $db->prepare($sql);
        // $stmt->bindValue(1,htmlspecialchars($filePath));
        // $stmt->execute();
        // unlink($filePath);
        // $success = 'ファイルをインポートしました。';
        unlink($filePath);
        fclose($file);
    }
    header('Location:table3.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="head">
            <p><span>⚙</span>二段階認証設定</p>
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
                <p><i class="fa-solid fa-clipboard-list"></i>CONFIRM</p>
            </div>
            <section class="register-bk">
            <div class="top2">
            <?php if($_SESSION['number'] === 1){?>
            <div class="contact">
                <h2>登録が完了しました。</h2>
                <p>登録した名前： <span><?= $_POST['register_name'] ?></span></p>
                <p>登録した荷物の数： <span><?= $_POST['register_number'] ?></span></p>
                <?php
                    $sql = 'SELECT MAX(id) FROM carry';
                    $db = getDb();
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    // userLogic::img($row);
                    if(is_uploaded_file($_FILES['img']['tmp_name'])){
                        $upload = './img/'.basename($_FILES['img']['name']);
                        move_uploaded_file($_FILES['img']['tmp_name'],$upload);
    
                        $sql = 'UPDATE carry SET img=? WHERE id=?';
                        $db = getDb();
                        $stmt = $db->prepare($sql);
                        $stmt->bindValue(1,$upload);
                        $stmt->bindValue(2,$row['MAX(id)']);
                        $stmt->execute();
                        print('登録した画像：');
                        print($_FILES['img']['name']);
                    }
    
                ?>
                <p>与えられたidは<span><?= $row["MAX(id)"]?></span>です。</p>
            </div>
            <?php }else if($_SESSION['number'] === 2){ ?>
                <?php if($_SESSION['id']) { ?>
                    <h3><?= $_SESSION['id'] ?></h3>
                <?php }else{ ?>
                    <h2>変更が完了しました。</h2>
                    <p>id: <?= $_SESSION['old']['id'] ?></p>
                    <p style="margin-top: 15px;">変更した名前</p>
                    <p>old:　<?= $_SESSION['old']['name'] ?> --> new:　<span style="color: red"><?= $_POST['update1'] ?></span></p>
                    <p style="margin-top: 15px;">変更した荷物の数:</p>
                    <p>old:　<?= $_SESSION['old']['number'] ?> --> new:　<span style="color: red"><?= $_POST['update2'] ?></span></p>
                <?php } ?>
            <?php }else if($_SESSION['number'] === 3) { ?>
            <?PHP if($_SESSION['delete_id']) { ?>
                    <h3><?= $_SESSION['delete_id'] ?></h3>
            <?php }else{ ?>
                <h2>削除が完了しました。</h2>
                <p style="margin-top: 10px;">削除したid:　<span style="color: red;"><?= $_POST['delete_id'] ?></span></p>
            <?php } ?>
            <?php }else{ ?>
                <?php if($err){ ?>
                    <?php
                    echo '<div style="color: red;">';
                    echo implode('<br>',$err);
                    echo '</div>';
                    ?>
                <?php }else if($success) { ?>
                    <h2><?= $success ?></h2>
                <?php } ?>
    
            <?php } ?>
        </div>
        <div class="a">
        <a href="register2.php">荷物登録画面</a><br>
        <a href="menu.php">メニュー画面</a><br>
        <a href="table3.php">テーブル一覧画面</a>
    </div>
    </div>


    </div>


<script src="sub.js"></script>
</body>
</html>