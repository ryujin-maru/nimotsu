<?php
// 暗号化するデータ
$data = 'Hello, World!';
$hello = 'Hello, World!';
 
// パスワード
$password = 'password1234';
 
// 利用可能な暗号化方式一覧
//$methods = openssl_get_cipher_methods();
 
// 暗号化方式
$method = 'aes-128-cbc';
 
// 方式に応じたIV(初期化ベクトル)に必要な長さを取得
$ivLength = openssl_cipher_iv_length($method);
 
// IV を自動生成
$iv = openssl_random_pseudo_bytes($ivLength);
 
// OPENSSL_RAW_DATA と OPENSSL_ZERO_PADDING を指定可
$options = 0;
 
// 暗号化
$encrypted = openssl_encrypt($data, $method, $password, $options, $iv);
var_dump($encrypted);
 
// 復号
$decrypted = openssl_decrypt($encrypted, $method, $password, $options, $iv);
var_dump($decrypted);

$encrypted = openssl_encrypt($hello, $method, $password, $options, $iv);
var_dump($encrypted);