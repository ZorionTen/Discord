<?php

namespace Console;

class DataCommand extends BaseCommand
{
    public function init()
    {
    }
    public function run()
    {
        if(count($this->argv)<3){
            $this->error("Invalid number of argumets ".count($this->argv));
            return false;
        }
        
        if($this->getArg("set")){
            $data = json_decode($this->argv[3],true)??505;
            $this->data->set(null,$data);
        } elseif($this->getArg("get")) {
            $this->info(json_encode($this->data->get()));
        } else {
            $this->error("Invalid argument ".$this->argv[2]);
        }
    }
}
