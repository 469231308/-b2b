<?php


namespace App\Module;

use JPush\Client as Jpush;


class JpushServer
{

    public static function getsms($arr){

    }


//    安卓发送
    public static function android_send(){
        $client = new Jpush(env("JPUSH_APP_KEY"),env("JPUSH_APP_MASTER_SECRET"));
//        整体推送
        $pusher = $client->push();
        $pusher->setPlatform('all');
        $pusher->addAllAudience();
        $pusher->setNotificationAlert('Hello, JPush');
        try {
            $pusher->send();
        } catch (\JPush\Exceptions\JPushException $e) {
            // try something else here
            print $e;
        }
    }


    // ios发送
    public static function ios_send(){
        $client = new Jpush(env("JPUSH_IOS_KEY"),env("JPUSH_IOS_MASTER_SECRET"));
//        整体推送
        $pusher = $client->push();
        $pusher->setPlatform('all');
        $pusher->addAllAudience();
        $pusher->setNotificationAlert('Hello, JPush');
        try {
            $pusher->send();
        } catch (\JPush\Exceptions\JPushException $e) {
            // try something else here
            print $e;
        }
    }



}
