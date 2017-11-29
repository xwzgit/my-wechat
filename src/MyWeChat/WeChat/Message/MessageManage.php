<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2017/11/27
 * Time: 12:01
 * Project Name: myWechat
 */

namespace MyWeChat\WeChat\Message;


class MessageManage
{
    protected $message = '';

    protected $sourceMessage = '';

    public function __construct()
    {
        libxml_disable_entity_loader(true);

        $receipt = file_get_contents("php://input");

        if ($receipt == null) {
            $receipt = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        //记录一下推送日志

        $postObj = simplexml_load_string($receipt, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj) {
            $this->message = json_decode(json_encode($postObj), true);
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
        return '<xml>' .
        '<ToUserName><![CDATA[' . $message['FromUserName'] . ']]></ToUserName>' .
        '<FromUserName><![CDATA[' . $message['ToUserName'] . ']]></FromUserName>' .
        '<CreateTime>' . time() . '</CreateTime>' .
        '<MsgType><![CDATA[' . $MsgType . ']]></MsgType>' .
        $contents . '</xml>';
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