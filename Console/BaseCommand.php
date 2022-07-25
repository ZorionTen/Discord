<?php

namespace Console;

class BaseCommand
{
    protected $colors=[
        "red"=>"\033[0;31m",
        "blue"=>"\033[0;34m",
        "green"=>"\033[1;32m",
        "yellow"=>"\033[1;33m",
        "nc"=>"\033[0m"
    ];
    public function init()
    {
    }
    public function run()
    {
    }

    protected function getOption($arg)
    {
        foreach($this->argv as $k=>$v){
            if($v==$arg){
                return $this->argv[$v+1];
            }
        }
        echo "No such parameter";
        return 404;
    }
    protected function getArg($arg)
    {
        foreach($this->argv as $k=>$v){
            if($v==$arg){
                return true;
            }
        }
        return false;
    }
    public function end(){
        echo PHP_EOL;
    }
    protected function error($text){
        echo $this->colors['red'].$text.$this->colors['nc'];
    }
    protected function info($text){
        echo $this->colors['green'].$text.$this->colors['nc'];
    }
    protected function print($text){
        echo $this->colors['yellow'].$text.$this->colors['nc'];
    }
}
