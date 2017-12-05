# 微信公众号开发实践
## 1、验证服务器消息
    
    public function index(Request $request)
    {
        $signature = $request->get('signature');
        $timestamp = $request->get('timestamp');
        $nonce = $request->get('nonce');

        $validate = new ValidateMsg();//初始化该验证类，调用验证方法即可

        if ($validate->validateToken($signature, $timestamp, $nonce, $token)) {
            return $request->get('echostr');
        } else {
            return '';
        }
    }

## 2、消息处理

    //初始化消息处理类：处理消息接收和解密操作（如果有密文）
    $params = [
                'token' => config('wechatC.token'),
                'encodingAesKey' => config('wechatC.encodeKey'),
                'appid' => config('wechatC.appid'),
                'timestamp' => $request->input('timestamp',''),
                'nonce' => $request->input('nonce',''),
                'msgSignatur' => $request->input('msg_signature',''),
                'encryptType' => $request->input('encrypt_type',''),
            ];
    $msgManage = new MessageManage($params);
        
    //获取推送的原始消息体，如果是密文的话就是密文格式
    $receipt = $msgManage->getSourceMessage() 
        
    //获取明文消息，数组格式数据
    $message = $msgManage->getMessage();
        
    //格式化成文本消息格式'<Content><![CDATA[' . $data['content'] . ']]></Content>'
    //$data内容根据实际消息类型而定
    //文本['content']; link:[title,description,url];image:[media_id]
    //vioce:[media_id]; video:[title,description,media_id]
    //news: [[title,description,url,picurl]]
    $content = $msgManage->textContent($data)
        
    //格式化成微信服务器可识别的xml数据，如果推送的消息是加密的话，将返回加密密文格式
    $response = $msg->convertResponseMessage($message, $MsgType, $contents)
    
## 3、加密解密
    
    //使用openssl_encrypt()进行加密，因为php7.1废弃了mcrypt加密
    //解密openssl_decrypt()解密
    //具体参见PrpCrypt.php文件