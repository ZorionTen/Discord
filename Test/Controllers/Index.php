<?php
namespace Controllers;
class Index{
    function init(){}
    function index(){
        $helper=new \Libs\Helper();
        echo "<br>";
        $this->config->set("test",[
            "test2"=>$helper->encrypt("StrPass02.")
        ],true);
        $this->data->set("test",[
            "test"=>true
        ]);

        $d= $this->config->get()["test"]['test2'];
        echo $helper->decrypt($d);
        die("<br>".__METHOD__);
    }
}