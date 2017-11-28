<?php
/**
 * 公众号开发的url配置文件
 * User: owner
 * Date: 2017/11/27
 * Time: 13:05
 * Project Name: myWechat
 */
return [
    'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={APPID}&secret={APPSECRET}',
    'menus' => [
        'menusInfo' => 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token={ACCESS_TOKEN}',
        'createMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token={ACCESS_TOKEN}',
        'getMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token={ACCESS_TOKEN}',
        'deleteMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={ACCESS_TOKEN}',
        'conditionalMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token={ACCESS_TOKEN}',
        'deleteConditionalMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token={ACCESS_TOKEN}',
        'testConditionalMenus' => 'https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token={ACCESS_TOKEN}'
    ]
];