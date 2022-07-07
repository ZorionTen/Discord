<?php

namespace Controllers;

use Libs\Curl;
use Libs\Logger;

class Bot
{
    function __construct()
    {
        $this->logger=new Logger();
    }
    public function init(){
        $token=base64_decode($this->config->get('bot')['auth_key']);
        $this->url = "https://api.telegram.org/bot${token}";
    }
    function index()
    {
        print_r($this->config->get("bot"));
        // print_r($this->config->config);
        echo "<br/>";
        echo "<a href='/index.php/bot/action'> Action </a>";
    }
    function getChats()
    {
        $this->init();
        $path="/getUpdates";
        $callPath=$this->url . $path;
        $data = Curl::call_json($callPath);
        print_r($data);
    }
    function runCommand($text=''){
        echo BASE_URI;
        echo Curl::call(BASE_URI."/index.php/discord/bot/action?m=test");
    }
    function updates(){
        $post=file_get_contents('php://input')??"NULL";
        $post=json_decode($post,true);
        $this->logger->write(json_encode($post['message']['entities']));
        $this->logger->write($post['message']['text']);
        if($post!="NULL"){
            if($post['message']['entities'] && $post['message']['entities']['type']=='bot_command')
            {
                $this->logger->write(true);
                $this->runCommand($post['message']['text']);
            }
        }
    }
    function setHook(){
        $this->init();
        $hook=BASE_URI."/index,php/Telegram/bot/updates";
        $path="/setWebhook";
        echo Curl::call($this->url.$path.'?url='.urlencode($hook));
    }
    function getLogs(){
        echo file_get_contents($this->logger->file);
    }
}
