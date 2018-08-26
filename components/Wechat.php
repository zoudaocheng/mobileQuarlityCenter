<?php
/**
 * Created by PhpStorm.
 * User: daocheng
 * Date: 17-8-11
 * Time: 下午5:56
 */

namespace app\components;


class Wechat
{
    public static function notify($data = []){
        $url = 'http://10.10.81.98:11007/mercury/stat/notify';
        return Soa::wechat_notice($url,$data);
    }
}