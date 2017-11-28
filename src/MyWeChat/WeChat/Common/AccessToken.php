<?php
/**
 * 获取AccessToken
 * User: owner
 * Date: 2017/11/27
 * Time: 13:02
 * Project Name: myWechat
 */

namespace MyWeChat\WeChat\Common;


use GuzzleHttp\Client;

class AccessToken
{
    private $appid;
    private $secret;

    /**
     * AccessToken constructor.
     * @param $appid
     * @param $secret
     */
    public function __construct($appid,$secret)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }

    /**
     * 获取用户token
     *
     * @return array
     */
    public function getAccessToken()
    {
        $url = Config::configItem('accessToken');
        if($url) {
            $url = str_replace('{APPID}',$this->appid,$url);
            $url = str_replace('{APPSECRET}',$this->secret,$url);
            $client = new Client();
            $response = $client->get($url,['timeout' => 30]);
            $content = $response->getBody()->getContents();
            try{
                $result = \GuzzleHttp\json_decode($content,true);
                return $result;
            } catch (MyException $e){
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }

        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'url 错误'
            ];
        }
    }
}