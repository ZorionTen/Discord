<?php

namespace Libs;

class Logger
{
    public function __construct()
    {
        $this->file=ROOT."/etc/log.log";
    }
    public function write($data)
    {
        $file=fopen($this->file,'a');
        fwrite($file,date('[H:i:s d-m-Y] ').$data.PHP_EOL);
        fclose($file);
    }
}
