<?php

namespace App\Jobs;

use App\Module\Logs;
use App\Module\SendsmsServer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class Email implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $arr_json;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr_json)
    {
        //赋值
        $this->arr_json = $arr_json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $json = $this->arr_json;
        $arr = json_decode($json);
//        Logs::logInfo('成功进入',$json,"sms_send",true);
        $res = SendsmsServer::send($arr);

        if($res['Code'] == "OK"){
            //打印日记
            Logs::logInfo('成功成功',$json,"sms_send",true);
        }else{
            //打印日记
            Logs::logInfo('发送失败',$json,"sms_error",true);
        }

    }
}
