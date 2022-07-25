<?php

namespace Libs;

class Crypto
{
    public $key = 3;
    public function encryptFile($str)
    {
        $text=base64_encode($str);
        $new_text=[];
        foreach(str_split($text,1) as $i){
            $new_text[]=dechex(ord($i)+$this->key)."/";
        }
        return rtrim(implode("",$new_text),'/');
    }
    public function decryptFile($str)
    {
        $new_str=[];
        foreach(explode("/",$str) as $i){
            $new_str[]=chr(intval(hexdec($i))-$this->key);
        }
        return base64_decode(implode("",$new_str));
    }
}
