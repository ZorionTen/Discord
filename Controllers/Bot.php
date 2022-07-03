<?php
namespace Controllers;

use Libs\Curl;

class Bot{
    function __construct()
    {
        $this->url="https://discord.com/api/v10";
    }
    function index(){
        print_r($this->config->get("bot"));
        // print_r($this->config->config);
        echo "<a href='/index.php/bot/startauth'> Auth </a>";
        echo "<br/>";
        echo "<a href='/index.php/bot/action'> Action </a>";
    }
    function action(){
        $path="/users/@me";
        echo $this->url.$path.PHP_EOL;
        $header=[];
        $agent=$this->config->get("bot")['agent'];
        // $header=["Authorization: Bot ".$this->config->get("bot")["auth_key"]];
        $header=["Authorization: Bearer ".$this->config->get("bot")["token"]['access_token']];
        $data=Curl::call_json($this->url.$path,"GET",null,$header,null);
        print_r($data);
    }
    function startAuth(){
        $data=[
            "client_id"=>$this->config->get("bot")['client'],
            // "client_secret"=>$this->config['bot']['secret'],
            "grant_type"=>"authorization_code",
            "redirect_uri"=>$this->config->get('redirect'),
            "state"=>"The_state",
            "response_type"=>"code",
            "permissions"=>8
        ];
        $scope="guilds guilds.join guilds.members.read messages.read identify bot";
        $url="https://discord.com/api/oauth2/authorize?scope=".str_replace("+","%20",urlencode($scope))."&".http_build_query($data);
        echo $url;
        header("location: $url");
    }
    function getToken(){
        $code=$_GET['code'];
        $data=[
            "client_id"=>$this->config->get('bot')['client'],
            "client_secret"=>$this->config->get('bot')['secret'],
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "redirect_uri"=>$this->config->get('redirect')
        ];
        $url="https://discord.com/api/oauth2/token";
        $token=Curl::call_json($url,"POST",$data);
        $config=$this->config->get('bot');
        $guild_id=$token['guild']['id'];
        $token['guild']=[];
        $token['guild'][]=$guild_id;
        $config['token']=$token;
        $this->config->set("bot",$config);
        print_r($token);
        header("location: /index.php/bot/index");
    }
}