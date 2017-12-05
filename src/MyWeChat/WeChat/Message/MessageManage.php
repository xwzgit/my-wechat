<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2017/11/27
 * Time: 12:01
 * Project Name: myWechat
 */

namespace MyWeChat\WeChat\Message;


use MyWeChat\WeChat\CryptLib\WeChatCrypt;

class MessageManage
{
    protected $isCrypt = false;
    protected $message = '';
    protected $weChatCrypt;

    protected $appId;
    protected $encodingAesKey;
    protected $token;

    protected $timestamp = '';
    protected $msgSignatur = '';
    protected $nonce = '';
    protected $encryptType = '';

    protected $sourceMessage = '';

    public function __construct($config = [])
    {
        //记录一下推送日志
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        $this->processXMl();
    }

    /**
     * 获取数据和处理
     */
    protected function processXMl()
    {
        libxml_disable_entity_loader(true);
        $receipt = file_get_contents("php://input");

        if ($receipt == null) {
            $receipt = $GLOBALS['HTTP_RAW_POST_DATA'];
        }


        $postObj = simplexml_load_string($receipt, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj) {

            $sourceMsg = json_decode(json_encode($postObj), true);
            //判断是否有密文，如果有密文，这进行解密处理同时设置该次为密文

            if (isset($sourceMsg['Encrypt']) && $this->msgSignatur) {
                $this->isCrypt = true;
                $this->weChatCrypt = new WeChatCrypt($this->token, $this->encodingAesKey,
                    $this->appId);
                //下面开始解密
                $decodeMsg = $this->weChatCrypt->decryptMsg(
                    $this->msgSignatur,
                    $this->timestamp,
                    $this->nonce,
                    $sourceMsg['Encrypt']
                );

                if($decodeMsg['errcode'] == '0') {
                    $postObj = simplexml_load_string($decodeMsg['decrypt'], 'SimpleXMLElement', LIBXML_NOCDATA);
                    $this->message = json_decode(json_encode($postObj), true);
                } else {
                    $this->message = $decodeMsg['errmsg'];
                }

            } else {
                $this->message = $sourceMsg;
            }
        }
        $this->sourceMessage = $receipt;
    }


    /**
     * 获取原始数据
     * @return mixed|string
     */
    public function getSourceMessage()
    {
        return $this->sourceMessage;
    }

    /**
     * 获取格式化后的消息数组
     *
     * @return mixed|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 统一实例化业务处理类
     * @param $MsgType
     * @return null
     */
    public function createMsgType($MsgType)
    {
        if (isset($this->msgTypes[$MsgType])) {
            return new $this->msgTypes[$MsgType];
        } else {
            return null;
        }
    }


    /**
     * 统一处理消息返回
     * 信息组装
     *
     * @param $message
     * @param $MsgType
     * @param $contents
     * @return string
     */
    public function convertResponseMessage($message, $MsgType, $contents)
    {
        $timestamp = time();

        $content = '<xml>' .
                '<ToUserName><![CDATA[' . $message['FromUserName'] . ']]></ToUserName>' .
                '<FromUserName><![CDATA[' . $message['ToUserName'] . ']]></FromUserName>' .
                '<CreateTime>' . $timestamp . '</CreateTime>' .
                '<MsgType><![CDATA[' . $MsgType . ']]></MsgType>' .
                $contents .
            '</xml>';

        if ($this->isCrypt) { //需要进行加密处理

            $pc = new WeChatCrypt($this->token,$this->encodingAesKey,$this->appId);
            $nonce =$pc->getRandomStr();

            $encode = $this->weChatCrypt->encryptMsg($content,$timestamp,$nonce);
            if($encode['errcode'] == '0') {

                return $this->generate($encode['encrypt'], $encode['signature'], $timestamp, $nonce);
            } else {

            }
        } else {
            return $content;
        }
    }

    /**
     * 生成xml消息
     * @param  $encrypt 加密后的消息密文
     * @param  $signature 安全签名
     * @param  $timestamp 时间戳
     * @param  $nonce 随机字符串
     *
     * @return string
     */
    public function generate($encrypt, $signature, $timestamp, $nonce)
    {
        $format = "<xml>
            <Encrypt><![CDATA[%s]]></Encrypt>
            <MsgSignature><![CDATA[%s]]></MsgSignature>
            <TimeStamp>%s</TimeStamp>
            <Nonce><![CDATA[%s]]></Nonce>
            </xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

    /**
     * 文本消息组装
     * @param $data
     * @return string
     */
    public function textContent($data)
    {
        return '<Content><![CDATA[' . $data['content'] . ']]></Content>';
    }

    /**
     * 文本消息组装
     * @param $data
     * @return string
     */
    public function linkContent($data)
    {
        return '<Title><![CDATA[' . $data['title'] . ']]></Title>
                <Description><![CDATA[' . $data['description'] . ']]></Description>
                <Url><![CDATA[' . $data['url'] . ']]></Url>';
    }

    /**
     * 处理图片
     * @param $data
     * @return string
     */
    public function imageContent($data)
    {
        return '<Image><MediaId><![CDATA[' . $data['media_id'] . ']]></MediaId></Image>';
    }

    /**
     * 语音图片
     * @param $data
     * @return string
     */
    public function voiceContent($data)
    {
        return '<Voice><MediaId><![CDATA[' . $data['media_id'] . ']]></MediaId></Voice>';
    }


    /**
     * 语音图片
     * @param $data
     * @return string
     */
    public function videoContent($data)
    {
        return '<Video>
                <MediaId><![CDATA[' . $data['media_id'] . ']]></MediaId>
                <Title><![CDATA[' . $data['title'] . ']]></Title>
                <Description><![CDATA[' . $data['description'] . ']]></Description>
                </Video> ';
    }


    /**
     *
     * 回复图文信息
     *
     *
     * @param $news
     * @return mixed
     */
    public function newsContent($news)
    {
        $new = '<ArticleCount>' . count($news) . '</ArticleCount>';
        $new .= '<Articles>';
        foreach ($news as $item) {
            $new .= '<item>
                        <Title><![CDATA[' . $item['title'] . ']]></Title> 
                        <Description><![CDATA[' . $item['description'] . ']]></Description>
                        <PicUrl><![CDATA[' . $item['picurl'] . ']]></PicUrl>
                        <Url><![CDATA[' . $item['url'] . ']]></Url>
                    </item>';
        }
        $new .= '</Articles>';
        return $new;
    }
}