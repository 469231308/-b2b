<?php


namespace App\Http\Controllers;


use App\Models\Emails;
use App\Jobs\Email;
use App\Module\Logs;
use App\Module\SendsmsServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use function AlibabaCloud\Client\json;

class SmsController extends Controller
{

    // TODO 短信入口 集中处理
    // TODO 集中表明 根据type区分
    // TODO type为  1 是 为注册短信   2   是 找回密码  3   是 app唤醒


    public function index(){
        $user = \DB::table("users")->get();
        var_dump($user);die;


        json(['code'=>123]);
    }

//    public function index(Request $request)
//    {
//        $data= $request->getContent();
//        $data = json_decode($data,true);
//        if(count($data) == count($data,1)){
//            $arr = $data;
//            if($arr['type'] == 1){
//                $type_txt = "注册短信";
//            }elseif ($arr['type'] == 2){
//                $type_txt = "找回密码";
//            }elseif ($arr['type'] == 3){
//                $type_txt = "app唤醒";
//            }
//            // TODO 记录消息数据已经存入  需按日期记录  需统计每日总共接受多少条
//            // 记录日志 time 发送给 mobile 的短信已经接受
//            $txt = "发送给".$arr['mobile']."的短信已接受,短信类型为".$type_txt;
//            Logs::logInfo('成功接受',$txt,'sms_receive',true);
////        var_dump(json_encode($arr));die();
//            //  将该短信存入队列中
//            $this->dispatchNow(new Email(json_encode($arr)));
//            return "ok";
//        }else{
//            foreach ($data as $k => $arr){
//                if($arr['type'] == 1){
//                    $type_txt = "注册短信";
//                }elseif ($arr['type'] == 2){
//                    $type_txt = "找回密码";
//                }elseif ($arr['type'] == 3){
//                    $type_txt = "app唤醒";
//                }
//                // TODO 记录消息数据已经存入  需按日期记录  需统计每日总共接受多少条
//                // 记录日志 time 发送给 mobile 的短信已经接受
//                $txt = "发送给".$arr['mobile']."的短信已接受,短信类型为".$type_txt;
//                Logs::logInfo('成功接受',$txt,'sms_receive',true);
//                //  将该短信存入队列中
//                $this->dispatchNow(new Email(json_encode($arr)));
//                return "ok";
//            }
//        }
//    }

    public function email(Request $request){
        $data= $request->getContent();
        $data = json_decode($data,true);
        $email = new Emails();
        $email->user_id = (int)$data['user_id'];
        $email->content = $data['content'];
        $email->create_time = date("Y-m-d H:i:s",time());
        $email->save();
        return "ok";
    }



}
