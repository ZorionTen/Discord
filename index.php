<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

define("HOSTNAME",$_SERVER["HTTP_HOST"]);
// die(HOSTNAME);
define("ROOT", __DIR__);

require_once ROOT."/vendor/autoload.php";
require_once ROOT."/helper.php";
require_once ROOT."/style/main.html";

function loader($class)
{
    global $module;
    $name = ucwords(str_replace("\\", "/", $class)) . ".php";
    $file=ROOT."/${module}/".$name;
    require_once $file;
}

$url = explode("?", $_SERVER["REQUEST_URI"])[0];
$url_array = explode("/", $url);

if(count($url_array)<5){
    die('Incomplete route');
}

$module = ucwords($url_array[2])??"404";
$controller = $url_array[3] ?? "index";
$action = $url_array[4] ?? "index";
$controller = ucwords($controller);

// define('BASE_URI','https://discordintigratetelegram.herokuapp.com');
define('BASE_URI',"https://".HOSTNAME);
define('MODULE',ROOT."/".$module);
spl_autoload_register("loader");
// print_r($url_array);

$classPath= "./${module}/Controllers/${controller}.php";
// echo $classPath;
if (file_exists($classPath)) {
    $controller = "Controllers\\" . $controller;
    $class = new $controller;
    $class->config = new Libs\Config();
    $class->data = new Libs\Model();
    $class->init();
    echo "<pre>";
    $class->$action();
    echo "</pre>";
} else {
    die("404 ${url}");
}
