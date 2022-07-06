<?php

namespace Libs;

class Curl
{
    static function call($path, $method = "GET", $data = false, $header = null, $useragent = null)
    {
        $curl = curl_init($path);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($useragent) {
            curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
        }
        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
        } elseif ($method == "GET") {
            //GET
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if ($data && is_array($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    static function call_json($path, $method = "GET", $data = [], $header = null, $useragent = null)
    {
        return json_decode(Self::call($path, $method, $data, $header, $useragent), true);
    }
}
