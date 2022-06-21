<?php
require_once 'connect.php';
require_once 'encode.php';
require_once 'userLogic.php';

class Logic {

    public static function deleteUser($s,$token) {
        $result = false;

        if(!isset($token) || $token !== $_SESSION['token']) {
            echo '不正アクセスの可能性があります';
            exit();
        }

        try {

            $sql = 'DELETE FROM carry WHERE id=?';
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,e($s));
            $stmt->execute();
            $result = true;

            return $result;
        }catch(Exception $e) {
            return false;
        }
    }

    public static function updateUser($id,$name,$number,$token) {
        $result = false;

        if(!isset($token) || $token !== $_SESSION['token']) {
            echo '不正アクセスの可能性があります';
            exit();
        }

        try {
            $sql = 'UPDATE carry SET id=?,name=?,number=? WHERE id=?';
            $db = getDb();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1,e($id));
            $stmt->bindValue(2,e($name));
            $stmt->bindValue(3,e($number));
            $stmt->bindValue(4,e($id));
            $stmt->execute();
            return $result = true;
        }catch(PDOException $e) {
            die($e->getMessage());
            return $result;
        }
    }

}

?>