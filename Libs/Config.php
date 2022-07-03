<?php

namespace Libs;

class Config
{
    public function __construct()
    {
        $this->file=ROOT."/etc/config.json";
        $this->config = json_decode(file_get_contents($this->file),true);
    }
    public function get($key = null)
    {
        if ($key) {
            return $this->config[$key]??[];
        } else {
            return $this->config;
        }
    }
    public function set(string $key,$val){
        $this->config[$key]=$val;
        file_put_contents($this->file,json_encode($this->config));
    }
}
