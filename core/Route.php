<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 16.01.16
 * Time: 23:33
 */
namespace core;


use core\exception\HttpException;

class Route {

    public $defaultController = "news";

    private $_route;
    private $_controller;
    private $_action;


    function __construct(Array $routes) {

        $this->routes = $routes;
    }

    function getURI() {

        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if (!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if (!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    function run() {

        $uri = $this->getURI();
        $m = parse_url($uri);
        if ($m) {
            $uri = $m['path'];
            $query = isset($m['query']) ? $m['query'] : "";
            parse_str($query, $parameters);
        }
        foreach ($this->routes as $pattern => $route) {

            if (!$uri) {
                $controllerFile = $this->getControllerFilePath($this->defaultController);
                $controllerClass = $this->getNamespace($controllerFile);
                $reflectionMethod = new \ReflectionMethod($controllerClass, "run");
                $reflectionMethod->invokeArgs(new $controllerClass(lcfirst($this->defaultController), null), []);
            }
            if ($pattern && preg_match("~$pattern~", $uri)) {
                if ($pattern != "") {
                    $internalRoute = preg_replace("~$pattern~", $route, $uri);
                } else {
                    $internalRoute = $uri;
                }

                $segments = explode('/', $internalRoute);

                $controllerId = lcfirst(array_shift($segments));
                $action = ucfirst(array_shift($segments));
                $parameters = $segments;
                $action = $action ? ucfirst($action) : '';
                $controllerFile = $this->getControllerFilePath($controllerId);
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                $controllerClass = $this->getNamespace($controllerFile);
                $reflectionMethod = new \ReflectionMethod($controllerClass, "run");
                $reflectionMethod->invokeArgs(new $controllerClass($controllerId, $action), $parameters);
            }
        }

        throw new HttpException('404', "The requested URL " . $uri . " was not found!");

    }

    public function getControllerFilePath($controllerId) {

        return BASE_PATH . DS . APP_DIR . DS . 'controllers' . DS . ucfirst($controllerId) . 'Controller.php';
    }

    public function getNamespace($fullPath) {

        $path = str_replace([BASE_PATH, '.php'], "", $fullPath);

        $namespace = "\\" . trim(str_replace("/", "\\", $path), '\\');

        return $namespace;
    }
}
