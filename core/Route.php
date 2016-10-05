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

    public $defaultController = "blog";

    private $_route;
    private $_controller;
    private $_action;


    public function __construct(array $routes) {

        $this->routes = $routes;
    }

    public function getURI() {

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

    /**
     * @throws HttpException
     */
    public function run() {

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
                $reflectionMethod->invokeArgs(new $controllerClass($this, lcfirst($this->defaultController), null), []);
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
                try {
                    $reflectionMethod->invokeArgs(new $controllerClass($this, $controllerId, $action), $parameters);
                } catch (\Exception $e) {
                    //TODO prints message Exception on site page
                    echo $e->getMessage();
                    var_dump($e->getTrace());
                    exit();
                }
            }
        }

        throw new HttpException('404', "The requested URL " . $uri . " was not found!");
    }

    /**
     * @param $controllerId
     *
     * @return string
     */
    public function getControllerFilePath($controllerId) {

        return BASE_PATH . DS . APP_DIR . DS . 'controllers' . DS . ucfirst($controllerId) . 'Controller.php';
    }

    /**
     * @param $fullPath
     *
     * @return string
     */
    public function getNamespace($fullPath) {

        $path = str_replace([BASE_PATH, '.php'], "", $fullPath);

        $namespace = "\\" . trim(str_replace("/", "\\", $path), '\\');

        return $namespace;
    }

    /**
     * @param     $url
     * @param int $statusCode
     */
    public function redirect($url, $statusCode = 302) {

        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = $this->getHostInfo() . $url;
        }
        header('Location: ' . $url, true, $statusCode);
        exit();
    }

    /**
     * @param $url
     * @param [] $params The parameter used in the route add to the parameters first
     *
     * @return mixed|string
     */
    public function createUrl($url, $params) {

        $resultUrl = null;
//TODO edit the URL of the configurations using
        if (is_array($params) && count($params) > 0) {
            $paramsQuery = http_build_query($params);
            if (count($this->routes)) {
                foreach ($this->routes as $pattern => $uri) {
                    if (preg_match("~" . trim($url, "/") . "~", $pattern)) {
                        $var = trim($url, "/") . "/" . array_shift($params);
                        if (preg_match("~{$pattern}~", $var)) {
                            $resultUrl = '/' . $var;
                            break;
                        }
                    }
                }
            }
            if (!$resultUrl) {
                $resultUrl = preg_replace('?', '', $url);
                $resultUrl .= '?' . $paramsQuery;
            }
        }
        if (!$resultUrl) {
            $resultUrl = $url;
        }

        return $resultUrl;
    }

    /**
     * @param string $schema
     */
    public function getHostInfo($schema = 'http') {

        if (isset($_SERVER['HTTP_HOST'])) {
            $hostInfo = $schema . '://' . $_SERVER['HTTP_HOST'];
        } else {
            $hostInfo = $schema . '://' . $_SERVER['SERVER_NAME'];
        }
    }
}
