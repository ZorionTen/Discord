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

$url = $_SERVER["REQUEST_URI"];
$url_arry = explode("/", $url);
$controller = $url_array[1] ?? "index";
$action = $url_array[2] ?? "index";
$controller=ucwords($controller);

if (file_exists("Controllers/${controller}.php")) {
    $controller="Controllers\\".$controller;
    (new $controller)->$action();
} else {
    die("404 ${url}");
}
