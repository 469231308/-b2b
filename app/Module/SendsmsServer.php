<?php


namespace App\Module;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\AlibabaCloudException;
use AlibabaCloud\Client\Exception\ServerException;

class SendsmsServer{


    // 短信模版
    public static $send_type = [
        "1" => "SMS_175355486",
        "2" => "SMS_175495710",
        "3" => "SMS_175485705",
    ];

    // 阿里云key 等
    public static $access = [
        // 加油站
        '0' => array('accessKetId'=>'LTAI4FrKa4VgojrcWnYnXM99','accessKeySecret'=>'juUzWdrv04y2MXMlZ4EznwYlOGCa9n','SignName'=>'义烽顺')

    ];

    // TODO 根据sms_type字段区分使用哪儿个模版
    // TODO 模版如下
    // SMS_175355486 注册短信  code
    // SMS_175495710 找回密码  password
    // SMS_175485705 app唤醒  无参数
    // key 义烽顺
    public static function send($arr){
        $arr = json_decode(json_encode($arr),true);
        if ($arr['type'] == "3"){
            $query_json = "";
        }else{
            $query = self::getquery($arr);
            $query_json = json_encode($query,true);
        }
        AlibabaCloud::accessKeyClient('LTAI4FrKa4VgojrcWnYnXM99', 'juUzWdrv04y2MXMlZ4EznwYlOGCa9n')
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $arr['mobile'],
                        'SignName' => "义烽顺",
                        'TemplateCode' => self::$send_type[$arr['type']],
                        'TemplateParam' => $query_json,
                        'OutId' => "1",
                    ],
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }

    }


    // TODO 模版如下
    // SMS_175355486 注册短信  code
    // SMS_175495710 找回密码  password
    // SMS_175485705 app唤醒  无参数
    public static function getquery($arr){
        if ($arr['type'] == "1"){
            $json_arr = array('code'=>$arr['code']);
        }elseif ($arr['type'] == "2"){
            $json_arr = array('password'=>$arr['code']);
        }
        return $json_arr;
    }


    // TODO 获取配置信息 根据code
    public static function getconfig($arr){
        $code = $arr['type'];
        $disk = \Storage::disk('local');
        $file = $disk->get('smsconfig.txt');
        $file_arr = json_decode($file,true);
        $config = $file_arr[$code];
        return $config;
    }

}

