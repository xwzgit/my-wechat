<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2017/11/27
 * Time: 12:01
 * Project Name: myWechat
 */

namespace MyWeChat\WeChat\Menu;


use GuzzleHttp\Client;
use MyWeChat\WeChat\Common\Config;
use MyWeChat\WeChat\Common\MyException;

class Menus
{

    /**
     * 获取自定义菜单
     *
     * @param $accessToken
     * @return array|mixed|string
     */
    public function getCustomMenus($accessToken)
    {
        $url = Config::configItem('menus.menusInfo');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->get($url,['timeout'=>30]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 自定义创建菜单
     *
     * @param $accessToken
     * @param $buttons
     * @return array|mixed|string
     */
    public function createMenus($accessToken,$buttons)
    {
        $url = Config::configItem('menus.createMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->post($url,[
                    'body' =>\GuzzleHttp\json_encode($buttons,JSON_UNESCAPED_UNICODE),
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 自定义菜单查询接口
     * 使用接口创建自定义菜单后，开发者还可使用接口查询自定义菜单的结构。
     * 另外请注意，在设置了个性化菜单后，使用本自定义菜单查询接口可以获取默认菜单和全部个性化菜单信息。
     *
     * @param $accessToken
     * @return array|mixed|string
     */
    public function getMenus($accessToken)
    {
        $url = Config::configItem('menus.getMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->get($url,[
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 自定义菜单删除
     * 使用接口创建自定义菜单后，开发者还可使用接口删除当前使用的自定义菜单。
     * 另请注意，在个性化菜单时，调用此接口会删除默认菜单及全部个性化菜单。
     *
     * @param $accessToken
     * @return array|mixed|string
     */
    public function deleteMenus($accessToken)
    {
        $url = Config::configItem('menus.deleteMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->get($url,[
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 创建个性化菜单
     *
     * @param $accessToken
     * @param $buttons
     * @return array|mixed|string
     */
    public function createConditionalMenus($accessToken,$buttons)
    {
        $url = Config::configItem('menus.conditionalMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->post($url,[
                    'body' =>\GuzzleHttp\json_encode($buttons,JSON_UNESCAPED_UNICODE),
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 测试个性化菜单
     *
     *
     * @param $accessToken
     * @param $conditional
     * @return array|mixed|string
     */
    public function tryConditionalMenus($accessToken,$conditional)
    {
        $url = Config::configItem('menus.testConditionalMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->post($url,[
                    'body' =>\GuzzleHttp\json_encode($conditional,JSON_UNESCAPED_UNICODE),
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }

    /**
     * 删除个性菜单
     *
     * @param $accessToken
     * @param $conditional
     * @return array|mixed|string
     */
    public function deleteConditionalMenus($accessToken,$conditional)
    {
        $url = Config::configItem('menus.deleteConditionalMenus');
        if($url) {
            $url = str_replace('{ACCESS_TOKEN}',$accessToken,$url);
            $client = new Client();
            try{
                $response = $client->post($url,[
                    'body' =>\GuzzleHttp\json_encode($conditional,JSON_UNESCAPED_UNICODE),
                    'timeout'=>30
                ]);
                $content = $response->getBody()->getContents();
                $content = \GuzzleHttp\json_decode($content,true);
                return $content;
            } catch (MyException $e) {
                return [
                    'errcode' => 1001,
                    'errmsg' => $e->getMessage()
                ];
            }
        } else {
            return [
                'errcode' => 1002,
                'errmsg' => 'Url 错误',
            ];
        }
    }
}