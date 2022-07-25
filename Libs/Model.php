<?php

namespace Libs;

class Model
{
    public function __construct()
    {
        $this->file = ROOT."/etc/data";
        try{
            $this->config = json_decode($this->crypto->decryptFile(file_get_contents($this->file)), true);
        } catch(\Exception $e){
            $this->data=[];
        }
    }
    public function get($key = null)
    {
        if ($key) {
            return $this->data[$key] ?? [];
        } else {
            return $this->data;
        }
    }
    public function getByIndex($key, $index)
    {
        if (is_array($this->data[$key])) {
            $c = 0;
            foreach ($this->data[$key] as $i) {
                if ($c == $index) {
                    return $i;
                } else {
                    $c++;
                }
            }
        } else {
            return $this->data[$key];
        }
    }
    public function set(string $key, $val)
    {
        $this->data[$key] = $val;
        file_put_contents($this->file, $this->crypto->encryptFile(json_encode($this->data)));
    }
}
