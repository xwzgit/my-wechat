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


use MyWeChat\WeChat\CryptLib\SHA1;

class ValidateMsg
{
    public function validateToken($signature, $timestamp, $nonce, $token)
    {
        $sha1 = new SHA1();
        $sign = $sha1->getSHA1($token, $timestamp, $nonce, '');
        if ($sign['errcode'] == '0' && $signature == $sign['sha1']) {
            return true;
        } else {
            return false;
        }
    }
}