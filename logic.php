<?php
require_once 'connect.php';
require_once 'encode.php';

class Logic {

    public static function deleteUser($s) {
        $result = false;

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

    public static function updateUser($id,$name,$number) {
        $result = false;

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