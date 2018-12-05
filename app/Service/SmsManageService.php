<?php
namespace App\Service;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Cache;

class Sms {
    private static $i = null;
    private $accessKeyId     = '';
    private $accessKeySecret = '';
    private $signName        = '';
    public $templateCode    = '';
    static $acsClient = null;

    private function __construct() {
        $this->accessKeyId     = ACCESS_KEY_ID;
        $this->accessKeySecret = ACCESS_KEY_SECRET;
        $this->signName        = SIGN_NAME;
        if(empty($this->templateCode)){
            $this->templateCode    = TEMPLATE_CODE;
        }

        // 加载区域结点配置
        Config::load();
    }

    public static function i() {
        if (self::$i instanceof Sms) {
            return self::$i;
        }

        return self::$i = new Sms();
    }

    public function send($mobile, $smscode=null,$name=null) {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($this->signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($this->templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode([  // 短信模板中字段的值
            "code" => $smscode
        ], JSON_UNESCAPED_UNICODE));

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    private function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $this->accessKeyId; // AccessKeyId

        $accessKeySecret = $this->accessKeySecret; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        if (static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
}

class SmsManageService{
    public static function generateSmscode($len = 4) {
        $chars    = ["0", "1", "2", "3", "5", "6", "7", "8", "9"];
        $charslen = count($chars) - 1;
        shuffle($chars);
        $arr = [];
        for ($i = 0; $i < $len; $i++) {
            $arr[] = $chars[mt_rand(0, $charslen)];
        }
        return implode('', $arr);
    }

    public static function checkSmscode($mobile, $smscode){
        $_smscode = Cache::get($mobile);
        if($smscode != $_smscode){
            return 115;
        }
        return null;
    }

    public static function sendSmsMessage($mobile, $smscode='', $templateCode="SMS_138066024",$name='') {
        if(Cache::has($mobile)){
            return 118;
        }
        $sms = Sms::i();
        $sms->templateCode=$templateCode;
        $ret = $sms->send($mobile, $smscode,$name);

        if (isset($ret->Code) && strtolower($ret->Code) == 'ok') {
            Cache::put($mobile, $smscode, 30000);
            return 113;
        } else {
            return 114;
        }
    }
}