<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Request;
use function AlibabaCloud\Client\json;
use function GuzzleHttp\Promise\all;

class SmsconfigController
{
    public function index(){
        return view('index');
    }

    public function addconfig(Request $request){
        $a = Request::all();
        $arr = [
          $a['code']=>array(
              'smstype'=>$a['smstype'],
              'appid'=>$a['appid'],
              'appsc'=>$a['appsc'],
              'signName'=>$a['signName'],
              )
        ];
        $b = json_encode($arr);

        // 创建文件
        $disk = \Storage::disk('local');
        $exists = $disk->exists('smsconfig.txt');

        if($exists == true){
            $file = $disk->get('smsconfig.txt');
            $file_arr = json_decode($file,true);
            $new_arr = array_merge($file_arr,$arr);
            $new_arr_json = json_encode($new_arr);
            $disk->put('smsconfig.txt', $new_arr_json);
        }else{
            // 创建一个文件
            $disk->put('smsconfig.txt', $b);
        }

        return 123;


    }
}
