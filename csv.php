<?php
session_start();

function putCsv($date) {
    try {
        $fileName = '荷物管理システム.csv';
        $res = fopen($fileName,'w');
        
        if($res === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました');
        }

        $header = ['id','name','number','img'];
        fputcsv($res,$header);

        forEach($date as $row) {
            mb_convert_variables("SJIS","UTF-8",$row);
            fputcsv($res,$row);
        }
        fclose($res);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Content-Length:'.filesize($fileName));
        header('Content-transfer-Encoding:binary');
        
        readfile($fileName);
    }catch(Exception $e) {
        die('エラーメッセージ:' . $e->getMessage());
    }
}

putCsv($_SESSION['csv']);
$_SESSION['csv'] = '';
?>