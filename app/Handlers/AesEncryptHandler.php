<?php

namespace App\Handlers;


class AesEncryptHandler
{
    protected static $firstEncryptKey ;
    protected static $secondEncryptKey ;

    public function __construct()
    {
        $config_lu_articleAesEncryptKey = Config::get('lu.article_aes_encrypt_key');
       self::$firstEncryptKey = $config_lu_articleAesEncryptKey['first_key'];
        self::$secondEncryptKey = $config_lu_articleAesEncryptKey['second_key'];
    }

    public static function securedEncrypt($input)
    {
        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $first_encrypted = openssl_encrypt($input,$method,self::$firstEncryptKey, OPENSSL_RAW_DATA ,$iv);
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, self::$secondEncryptKey, TRUE);

        $output = base64_encode($iv.$second_encrypted.$first_encrypted);
        return $output;
    }

    public static function securedDecrypt($input)
    {
        $mix = base64_decode($input);

        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);

        $iv = substr($mix,0,$iv_length);
        $second_encrypted = substr($mix,$iv_length,64);
        $first_encrypted = substr($mix,$iv_length+64);

        $data = openssl_decrypt($first_encrypted,$method,self::$firstEncryptKey,OPENSSL_RAW_DATA,$iv);
        $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, self::$secondEncryptKey, TRUE);

        if (hash_equals($second_encrypted,$second_encrypted_new))
            return $data;

        return false;
    }


}
