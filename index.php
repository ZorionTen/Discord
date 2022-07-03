<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


function loader($class){
    $name=ucwords(str_replace("\\","/",$class)).".php";
    // echo $name;
    // die;
    require_once $name;
}
spl_autoload_register("loader");

define("ROOT",__DIR__);

$url = explode("?",$_SERVER["REQUEST_URI"])[0];
$url_array = explode("/", $url);
$controller = $url_array[2] ?? "index";
$action = $url_array[3] ?? "index";
$controller=ucwords($controller);

// print_r($url_array);

if (file_exists("Controllers/${controller}.php")) {
    $controller="Controllers\\".$controller;
    $class=new $controller;
    $class->config=new Libs\Config();
    echo "<pre>";
    $class->$action();
    echo "</pre>";
} else {
    die("404 ${url}");
}
