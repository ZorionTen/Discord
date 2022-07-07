<?php

namespace Controllers;

use Libs\Curl;

class Bot
{
    function __construct()
    {
        $this->url = "https://discord.com/api/v10";
    }
    function init(){
        $this->header = [
            "Authorization: Bot " . base64_decode($this->config->get("bot")["auth_key"]),
        ];
    }
    function index()
    {
        print_r($this->config->get("bot"));
        // print_r($this->config->config);
        echo "<a href='/index.php/bot/startauth'> Auth </a>";
        echo "<br/>";
        echo "<a href='/index.php/bot/action'> Action </a>";
    }
    function action()
    {
        $channel=$this->data->getByIndex("channels",0);
        $path="/channels/${channel}/messages";
        $content=[
            "content"=>base64_decode($_GET['m']??base64_encode("ping"))
        ];
        $data = Curl::call_json($this->url . $path, "POST", $content, $this->header, null);
        // print_r($data);
        // echo json_encode($data);
        echo json_encode(['success']);
    }
    function updateConfig(){
         if (!$this->getChannels()) {
            echo "FAIL ".__METHOD__.PHP_EOL;
            return false;
        } else {
            echo $this->config->get();
        }
    }
    function getChannels()
    {
        if (!$this->getGuilds()) {
            echo "FAIL".__METHOD__.PHP_EOL;
            return false;
        }
        $guild = $this->data->getByIndex("guilds", 0);
        $url = $this->url . "/guilds/${guild}/channels";
        $data = Curl::call_json($url, null, null, $this->header, null);
        if (isset($data['message'])) {
            print_r($data);
            return false;
        }
        $channels = [];
        foreach ($data as $i) {
            if ($i["type"] == 0) {
                $channels[$i["name"]] = $i["id"];
            }
        }
        $this->data->set('channels', $channels);
        return true;
    }
    function getGuilds()
    {
        $path = "/users/@me/guilds";
        $data = Curl::call_json($this->url . $path, null, null, $this->header, null);
        if (isset($data['message'])) {
            print_r($data);
            return false;
        }
        $guilds = [];
        foreach ($data as $i) {
            $guilds[$i['name']] = $i['id'];
        }
        $this->data->set("guilds", $guilds);
        return true;
    }
    function startAuth()
    {
        $data = [
            "client_id" => $this->config->get("bot")['client'],
            // "client_secret"=>$this->config['bot']['secret'],
            "grant_type" => "authorization_code",
            "redirect_uri" => $this->config->get('redirect'),
            "state" => "The_state",
            "response_type" => "code",
            "permissions" => 8
        ];
        $scope = $this->config->get('scopes');
        $url = "https://discord.com/api/oauth2/authorize?scope=" . str_replace("+", "%20", urlencode($scope)) . "&" . http_build_query($data);
        echo $url;
        header("location: $url");
    }
    function getToken()
    {
        $code = $_GET['code'];
        $data = [
            "client_id" => $this->config->get('bot')['client'],
            "client_secret" => $this->config->get('bot')['secret'],
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $this->config->get('redirect')
        ];
        $url = "https://discord.com/api/oauth2/token";
        $token = Curl::call_json($url, "POST", $data);
        $config = $this->config->get('bot');
        $guild_id = $token['guild']['id'];
        $token['guild'] = [];
        $token['guild'][] = $guild_id;
        $config['token'] = $token;
        $this->data->set("bot", $config);
        print_r($token);
        header("location: /index.php/bot/index");
    }
}
