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
     * @param bool $store
     * @return array
     */
    public function getAccessToken($store = false)
    {
        if($store) {
            if(is_file('./TokenStore.log')) {
                $content = @file_get_contents('./TokenStore.log');

                if($content) {
                    $params = explode(':',$content);
                    $params[] = time();
                    if(($params[2] - time()) > 0) {
                        return [
                            'code' => 0,
                            'access_token' => $params[1]
                        ];
                    }
                }
            }
        }

        $url = Config::configItem('accessToken');
        if($url) {
            $url = str_replace('{APPID}',$this->appid,$url);
            $url = str_replace('{APPSECRET}',$this->secret,$url);
            $client = new Client();
            $response = $client->get($url,['timeout' => 30]);
            $content = $response->getBody()->getContents();
            try{
                $result = \GuzzleHttp\json_decode($content,true);
                if(isset($result['access_token'])) {
                    $token = $this->appid.':'.$result['access_token'].':'.strtotime('+ 7000 seconds');
                    @file_put_contents('./TokenStore.log',$token);
                    return [
                        'code' => 0,
                        'access_token' => $result['access_token']
                    ];
                } else {
                    return [
                        'code' => 1000,
                        'error_msg' => $result['errmsg']
                    ];
                }
            } catch (MyException $e){
                return [
                    'code' => 1001,
                    'error_msg' => $e->getMessage()
                ];
            }

        } else {
            return [
                'code' => 1002,
                'error_msg' => 'url 错误'
            ];
        }
    }
}