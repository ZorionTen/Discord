<?php

namespace Libs;

use Libs\Crypto;

class Config
{
    public function __construct()
    {
        $this->crypto = new Crypto();
        $this->file = ROOT . "/etc/config";
        try {
            $this->config = json_decode($this->crypto->decryptFile(file_get_contents($this->file)), true);
        } catch (\Exception $e) {
            $this->config = [];
        }
    }
    public function get($key = null)
    {
        if ($key) {
            return $this->config[$key] ?? [];
        } else {
            return $this->config;
        }
    }
    public function set(string $key = null, $val)
    {
        if ($key) {
            $this->config[$key] = $val;
        } else {
            $this->config=$val;
        }
        file_put_contents($this->file, $this->crypto->encryptFile(json_encode($this->config)));
    }
}
