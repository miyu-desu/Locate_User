<?php
function encrypt($text, $key){
    return urlencode(base64_encode(openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA,  chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0))));
}
function decrypt($text, $key){
    $dec = openssl_decrypt(base64_decode(urldecode($text)), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0));
    if(empty($dec)){ return '데이터복호화 실패'; }else{ return $dec; }
}