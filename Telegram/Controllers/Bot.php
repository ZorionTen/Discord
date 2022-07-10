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
        zlog(time());
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
        $text=urlencode(base64_encode($text));
        // $this->logger->write("PRINT: ".$text);
        $this->logger->write(Curl::call(BASE_URI."/index.php/discord/bot/action?m=${text}"));
    }
    function getWebhook(){
        $data=Curl::call_json($this->url."/getWebhookInfo");
        print_r($data);
    }
    function updates(){
        $post=file_get_contents('php://input');
        if($post!=""){
            $post=json_decode($post,true);
            // $this->logger->write(json_encode($post['message']['entities'][0]['type']));
            zlog(json_encode($post['message']));
            $this->logger->write($post['message']['text']);
            if($post['message']['entities'] && $post['message']['entities'][0]['type']=='bot_command')
            {
                $this->logger->write('TRUE');
                $message=$post['message']['from']['first_name'].": ".str_replace("/post","",$post['message']['text']);
                $this->runCommand($message);
            }
        } else {
            $this->logger->write("NULL POST");
        }
    }
    function setHook(){
        $this->init();
        $hook=BASE_URI."/index.php/telegram/bot/updates";
        $path="/setWebhook";
        echo Curl::call($this->url.$path.'?url='.urlencode($hook));
        $this->getWebhook();
    }
    function getLogs(){
        echo file_get_contents($this->logger->file);
    }
}
