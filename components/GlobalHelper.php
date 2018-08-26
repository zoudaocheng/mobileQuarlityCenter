<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/5/13
 * Time: 16:12
 */

namespace app\components;


class GlobalHelper
{
    public static function str2arr($string,$delimiter = PHP_EOL) {
        $items = explode($delimiter,$string);
        foreach ($items as $key => &$item){
            $pos = strpos($items,'#');//查找 # 的位置
            if(0 === $pos){
                unset($items[$key]); // # 开头，整行需要注释掉
                continue; //再下一个
            }
            if(0 < $pos){
                $item = substr($item,0,$pos);// # 在中间，后面一段需要注释掉
            }
            $item = trim($item);
            if(empty($item)){
                unset($items[$key]);
            }
        }
        return $items;
    }

    /**
     * 转换成utf-8
     * @param $text
     * @return mixed
     */
    public static function convert2utf8($text) {
        $encoding = mb_detect_encoding($text,mb_detect_order(),false);
        if('UTF-8' == $encoding){
            $text = mb_detect_encoding($text,'UTF-8','UTF-8');
        }
        $out = iconv(mb_detect_encoding($text,mb_detect_order(),false),'UTF-8//IGNORE',$text);
        return $out;
    }
}