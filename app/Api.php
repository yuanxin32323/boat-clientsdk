<?php

namespace Boat\Client;

/**
 * 开发者SDK
 */
class Api {

    private $open_id;
    private $curl;
    private $url;
    private $session_key;
    private $skey = '';
    private $rsa;

    /**
     * 初始化
     * @param type $open_id 应用ID
     * @param type $pub_key 传输秘钥
     */
    public function __construct($open_id, $pub_key, $url = '') {
        $this->rsa = new Rsa($pub_key);
        $this->open_id = $open_id;
        $this->curl = new \lisao\curl\curl('');
        $this->session_key = $this->random_str(32); //初始化会话秘钥
        if ($url) {
            $this->url = $url;
        } else {
            $this->url = 'http://boat2.qqplugin.com';
        }
    }

    /**
     * 创建会话
     * @return type
     */
    public function create() {
        $this->curl->setUrl($this->url . '/api/session/create');
        $post = [
            'open_id' => $this->open_id,
            'key' => $this->rsa->publicEncode($this->session_key),
            'nonce' => $this->random_str(32),
            'timestamp' => time()
        ];


        $result = json_decode($this->curl->post($post), true);
        if (!$this->checkSign($result)) {
            return FALSE;
        }
        if ($result['error'] == 0) {
            $this->skey = $result['skey'];
        }
        return $result;
    }

    /**
     * 登录授权
     * @param type $tags
     * @return type
     */
    public function login($tags) {
        $this->curl->setUrl($this->url . '/api/session/login');
        $post = [
            'skey' => $this->skey,
            'tags' => $tags,
            'nonce' => $this->random_str(32),
            'timestamp' => time()
        ];
        $post['sign'] = $this->sign($post);

        $result = json_decode($this->curl->post($post), true);
        if (!$this->checkSign($result)) {
            return FALSE;
        }
        return $result;
    }

    /**
     * 计算签名
     * @param array $arr 待签名数组
     * @return string md5签名
     */
    private function sign($arr) {
        $temp = [];
        foreach ($arr as $val) {

            $temp[] = $val;
        }

        sort($temp, SORT_STRING);
        $str = implode('', $temp);
        return md5($str . $this->session_key);
    }

    /**
     * 验证签名
     * @param array $arr 待签名数组
     * @return string md5签名
     */
    public function checkSign($arr) {
        $temp = [];
        foreach ($arr as $k => $val) {
            if ($k != 'sign') {
                $temp[] = $val;
            }
        }

        sort($temp, SORT_STRING);
        $str = implode('', $temp);
        $sign = md5($str . $this->session_key);
        if ($sign == $arr['sign']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 取随机字符
     */
    private function random_str($length = 32, $number = false) {
        $dictionary = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; //62位
        if ($number) {
            $dictionary = "0123456789";
        }
        $str = '';

        $max = strlen($dictionary) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $dictionary[rand(0, $max)];
        }
        return $str;
    }

}
