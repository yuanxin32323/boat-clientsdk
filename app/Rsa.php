<?php

namespace Boat\Client;

/**
 * Rsa 非对称加密
 */
class Rsa {

    private $pri_k;
    private $pub_k;

    /**
     * 初始化
     * @param type $pub_k 公钥
     * @param type $pri_k 私钥
     */
    public function __construct($pub_k = '', $pri_k = '') {
        $this->pri_k = $pri_k;
        $this->pub_k = $pub_k;
    }

    /**
     * 获取私钥
     */
    public function getPrivateKey() {
        return $this->pri_k;
    }

    /**
     * 获取公钥
     */
    public function getPublicKey() {
        return $this->pub_k;
    }

    /**
     * 生成秘钥
     * @param type $bits
     * @return boolean
     */
    public function create($bits = 1024) {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => $bits, //字节数    512 1024  2048   4096 等
            "private_key_type" => OPENSSL_KEYTYPE_RSA, //加密类型
        );
        $res = openssl_pkey_new($config);
        if (!$res) {
            return FALSE;
        }
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        $this->pub_k = $public_key["key"];
        $this->pri_k = $private_key;
        openssl_free_key($res);
        return TRUE;
    }

    /**
     * 私钥解密
     * @param type $data
     * @return string
     */
    public function privateDecode($data) {
        $decrypted = '';
        $text = base64_decode($data);
        openssl_private_decrypt($text, $decrypted, $this->pri_k);
        return $decrypted;
    }

    /**
     * 公钥加密
     * @param type $data
     * @return string
     */
    public function publicEncode($data) {
        $decrypted = '';

        openssl_public_encrypt($data, $decrypted, $this->pub_k);

        return base64_encode($decrypted);
    }

    /**
     * 签名
     * @param type $content
     * @return type
     */
    public function sign($content) {
        $privKeyId = openssl_pkey_get_private($this->pri_k);
        $signature = '';
        openssl_sign($content, $signature, $privKeyId);
        openssl_free_key($privKeyId);
        return base64_encode($signature);
    }

    /**
     * 验签
     * @param type $content
     * @param type $sign
     * @return type
     */
    public function checkSign($content, $sign) {
        $publicKeyId = openssl_pkey_get_public($this->pub_k);
        $result = openssl_verify($content, base64_decode($sign), $publicKeyId);
        openssl_free_key($publicKeyId);
        return $result === 1 ? true : false;
    }

}
