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
}

// ko

?>