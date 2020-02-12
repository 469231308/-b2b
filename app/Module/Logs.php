<?php


namespace App\Module;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Logs
{

    // 文件记录类型 1：文件目录/日期/文件名  2：所有日志一个文件夹
    public function __construct()
    {

    }


    /**
     * @param $message
     * @param array $data
     * @param string $filename
     * @param string $isDate 是否按月份分文件夹
     */
    private static function _save($message, $data = [], $filename = 'log', $isDate = false)
    {
        $log = new Logger('mylog');
        if (PHP_SAPI == 'cli') {
            // 命令行访问脚本的，加一个cli标识和用户浏览器访问的区分开
            $filename .= '_cli';
        }

        $filename = $filename . '.log';

        if ($isDate) {
            // 是否要按日显示
            $path = storage_path('logs/' . date('Ymd'));
        } else {
            $path = storage_path('logs/');
        }

        // 有时候运维没给号权限，容易导致写入日志失败
        self::mkDirs($path);

        $path = $path . '/' . $filename;
        if (gettype($data) != 'array') {
            $message .= "：" . $data;
            $data = [];
        }

        $log->pushHandler(new StreamHandler($path, Logger::INFO));
        $log->addInfo($message, $data);

//        $log->addError($message, $data);
    }

    public static function logInfo($message, $data = [], $filename = 'info', $isDate = false)
    {
        self::_save($message, $data, $filename, $isDate);
    }

    /**
     * @param $message
     * @param array $data
     * @param string $filename
     */
    public static function logError($message, $data = [], $filename = 'error')
    {
        // 错误日志不会太多，按单文件记录可以了，默认$isDate=false
        self::_save($message, $data, $filename, false);
    }


    /**
     * 给日志文件夹权限
     * @param $dir
     * @param int $mode
     * @return bool
     */
    public static function mkDirs($dir, $mode = 0777)
    {

        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return TRUE;
        }
        if (!self::mkdirs(dirname($dir), $mode)) {
            return FALSE;
        }
        return @mkdir($dir, $mode);
    }


    public static function sql()
    {
        \DB::listen(function ($sql) {
            foreach ($sql->bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $sql->bindings[$i] = "'$binding'";
                    }
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
            $query = vsprintf($query, $sql->bindings);
            Logs::info('sql:', $query, 'sql', false);
        });
    }

}
