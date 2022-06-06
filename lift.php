<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二段階認証設定</title>
    <link rel="stylesheet" href="style.css"></link>
</head>
<body>
    <div class="login-box">
        <p>二段階認証設定を解除しますか？</p>
        <form method="POST" action="https://nimotsu.refine-web.co.jp/nimotsu/lift2.php">
            <input type="submit" name="yes" value="YES">
            <a href="menu.php"><input type="button" value="NO"></a>
        </form>
    </div>
</body>
</html>