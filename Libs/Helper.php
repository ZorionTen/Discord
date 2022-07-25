<?php

namespace Libs;

class Helper
{
    public static $key = 3;
    public static function encrypt($str)
    {
        if (is_array($str)) {
            $arr=[];
            foreach ($str as $k=>$i) {
                $arr[$k]=self::encrypt($i);
            }
            return $arr;
        } else {
            $str = base64_encode($str);
            $enced=[];
            foreach (str_split($str,1) as $k=>$i) {
                $enced[]=dechex(ord($i)+self::$key).":";
            }
            $enced[]="_ENC_";
            return implode("",$enced);
        }
    }
    public static function decrypt($str)
    {
        if (is_array($str)) {
            $arr=[];
            foreach ($str as $k=>$i) {
                $arr[$k]=self::encrypt($i);
            }
            return $arr;
        } else {
            $str = rtrim($str,":_ENC_");
            $enced=[];
            foreach (explode(":",$str) as $k=>$i) {
                $enced[]=chr(hexdec($i)-self::$key);
            }
            return base64_decode(implode("",$enced));
        }
    }
}
