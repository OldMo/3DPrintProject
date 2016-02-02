<?php

/**
 * Created by PhpStorm.
 * User: Liuxingyun
 * Date: 2016/1/18
 * Time: 20:29
 */
class Log
{
    function addLog($message,$logName){
        $logName = 'logs/'.date('YmdHi', time()).'-crawl.log';
        error_log($message." \r\n", 3, $logName);
    }

}