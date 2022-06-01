<?php
require_once 'connect.php';
$err = '';
if(isset($_POST['up'])) {
    if(is_uploaded_file($_FILES['csvFile']['tmp_name'])) {
        $filePath = $_FILES['csvFile']['tmp_name'];
        $fileName = $_FILES['csvFile']['name'];
    
        if(pathinfo($fileName,PATHINFO_EXTENSION) != 'csv') {
            $err = 'ファイルの拡張子はcsvのみです。';
        }else {
            $sql = "LOAD DATA INFILE ? INTO TABLE carry CHARACTER SET sjis FIELDS TERMINATED BY ',' IGNORE 1 LINES (@1,@2,@3) SET name=@2,number=@3";
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,htmlspecialchars($filePath));
            $stmt->execute();
            unlink($filePath);
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
    <title>Document</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="csvFile">
        <input type="submit" name="up" value="アップロード">
    </form>
</body>
</html>