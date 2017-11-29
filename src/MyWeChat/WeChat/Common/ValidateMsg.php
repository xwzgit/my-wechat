<?php
/**
 * 微信通信校验
 *
 * User: owner
 * Date: 2017/11/27
 * Time: 13:02
 * Project Name: myWechat
 */

namespace MyWeChat\WeChat\Common;


class ValidateMsg
{
    public function validateToken($signature, $timestamp, $nonce, $token)
    {
        $sign = $this->getSHA1($token, $timestamp, $nonce, '');
        if ($signature == $sign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通信加密
     *
     * @param $token
     * @param $timestamp
     * @param $nonce
     * @param $encrypt_msg
     * @return string
     */
    protected function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        //排序
        try {
            $array = [$encrypt_msg, $token, $timestamp, $nonce];
            sort($array, SORT_STRING);
            $str = implode($array);
            return sha1($str);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}