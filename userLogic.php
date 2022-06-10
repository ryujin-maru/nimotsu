<?php
require_once 'connect.php';
require_once 'encode.php';
class userLogic {

    public static function userMail($em){
        $result = false;
        $sql = 'SELECT * FROM pre_de_member WHERE mail=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,$em);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return $result = true;
        }else{
            return $result;
        }
        
    }

    public static function register($urltoken,$em) {
        $result = false;
        try {
            $sql = 'INSERT INTO pre_de_member(urltoken,mail,date) VALUE (?,?,now())';
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,$urltoken);
            $stmt->bindValue(2,$em);
            $stmt->execute();
            return $result = true;
        }catch(PDOException $e){
            die($e->getMessage());
            return $result;
        }

    }

    public static function check($urltoken) {
        $sql = 'SELECT mail FROM pre_de_member WHERE urltoken=? and flag=0 AND date >= now() - interval 5 minute';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($urltoken));
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result == false){
            return $result = false;
            
        }else{
            return $result['mail'];
            
        }
    }

    public static function complete($n,$p) {
        $result = false;
        try {
            $sql = 'INSERT INTO free(name,mail,pass) VALUE (?,?,?)';
            $db = getDb();
            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,e($n));
            $stmt->bindValue(2,e($_SESSION['mail']));
            $stmt->bindValue(3,e($p));
            $stmt->execute();

            $sql2 = 'UPDATE pre_de_member SET flag=1 WHERE mail=?';
            $stmt = $db->prepare($sql2);
            $stmt->bindValue(1,e($_SESSION['mail']));
            $stmt->execute();

            $db->commit();
            session_destroy();

            return $result = true;
        }catch(PDOException $e) {
            $db->rollback();
            return $result;
            die('エラー:' . $e->getMessage());
        }
    }

    public static function delete($urltoken) {
        $sql = 'DELETE FROM pre_de_member WHERE urltoken=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($urltoken));
        $stmt->execute();
    }

    public static function timeOut() {
        try {
            $sql = 'DELETE FROM pre_de_member WHERE date < now() - interval 5 minute AND flag=0' ;
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->rowCount();
            return $result;
        }catch(PDOException $e) {
            die('エラー：' . $e->getMessage());
        }
    }
    public static function flag($name) {
        $result = false;
        $sql = 'SELECT * FROM free WHERE name=? AND flag=1';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($name));
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if($r) {
            $result = true;
            return $result;
        }else{
            return $result;
        }
    }

    public static function second($secret,$q,$u) {
        $sql = 'UPDATE free SET flag=0,skey=?,qr=? WHERE name=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($secret));
        $stmt->bindValue(2,e($q));
        $stmt->bindValue(3,e($u));
        $stmt->execute();
    }

    public static function key($u) {
        $sql = 'SELECT skey,qr FROM free WHERE name=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($u));
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function lift($n) {
        $result = false;
        $sql = 'UPDATE free SET flag=1 WHERE name=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,$n);
        $stmt->execute();
        $r = $stmt->rowcount();
        if($r != 0) {
            return $result = true;
        }else{
            return $result;
        }

    }

    public static function img($id) {
        $result = false;
        if(is_uploaded_file($_FILES['img']['tmp_name'])) {
            $pathFile = $_FILES['img']['tmp_name'];
            $pathName = $_FILES['img']['name'];
            if(pathinfo($pathName,PATHINFO_EXTENSION) !== 'png' && pathinfo($pathName,PATHINFO_EXTENSION) !== 'jpg' && pathinfo($pathName,PATHINFO_EXTENSION) !== 'img') {
                return $result;
            }else{
                $uploaddir = './img/';
                $upload = $uploaddir . basename($pathName);
                move_uploaded_file($pathFile,$upload);
                $result = true;
            }

            if($result == true) {
                $sql = 'UPDATE carry SET img=? WHERE id=?';
                $db = getDb();
                $stmt = $db->prepare($sql);
                $stmt->bindValue(1,$upload);
                $stmt->bindValue(2,e($id));
                $stmt->execute();
                return $result;
            }
        }else{
            return $result;
        }

    }

    public static function png($id) {
        $sql = 'SELECT img FROM carry WHERE id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($id));
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['img'] == NULL) {
            return $result = false;
        }else{
            return $row['img'];
        }
    }

    public static function delete_img($id) {
        $result = false;

        $sql = 'SELECT img FROM carry WHERE id=?';
        $db = getDb();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1,e($id));
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!unlink($row['img'])){
            echo '削除するファイル名がありません';
        }

        try {
            $sql = 'UPDATE carry SET img=DEFAULT(img) WHERE id=?';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,e($id));
            $stmt->execute();
            return $result = true;
        }catch(PDOException $e) {
            return $result;
        }
    }

}


?>