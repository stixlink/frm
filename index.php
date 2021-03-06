<?php
define("BASE_PATH", __DIR__);
define("APP_DIR", "app");
define("DS", DIRECTORY_SEPARATOR);
include(BASE_PATH . DS . "core" . DS . "Autoload.php");
core\Autoload::run();
$routes = include(BASE_PATH . DS . APP_DIR . DS . "config" . DS . "route.php");
$route = new \core\Route($routes);
try {
    $route->run();
} catch (\Exception $e) {
    //TODO prints message Exception on site page
    var_dump($e->getMessage());
    exit();
}
