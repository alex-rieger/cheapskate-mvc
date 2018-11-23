<?php

namespace Cheapskate;

/**
 * Class AppRouter
 * @package Cheapskate
 * Handles routing
 */
class AppRouter {

    /**
     * @var string
     */
    protected $routingConfigFile = __DIR__ . '/../config/routing.ini';

    /**
     * @var mixed | array
     */
    protected $routes;

    /**
     * AppRouter constructor.
     */
    public function __construct()
    {
        $this->getRoutes();
    }

    /**
     * @return array
     */
    public function parseUrl()
    {
        $route = 'index';
        $params = [];

        if (isset($_GET['url'])) {
            $parsedUrl = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

            // set requested route and params
            $route = $parsedUrl[0];
            unset($parsedUrl[0]); // unset this, so remaining values can be passed as params
            $params = array_values($parsedUrl);
        }

        return[
            'route' => $route,
            'params' => $params
        ];
    }

    /**
     * @param $requestUrl
     * @return bool
     */
    public function isValidRoute($requestUrl)
    {
        return $this->requestRouteIsInIni($requestUrl) && $this->requestControllerAndMethodExist($requestUrl);
    }

    /**
     * @param $requestRoute
     * @return array
     */
    public function getClassParamsForInstance($requestRoute)
    {
        $controller = $this->getControllerNameFromRequestRoute($requestRoute);
        $method = $this->getMethodNameFromRequestRoute($requestRoute);
        $controllerFileName = $this->getControllerFileNameFromControllerName($controller);

        return [
            'controller' => $controller,
            'method' => $method,
            'filename' => $controllerFileName
        ];
    }

    /**
     * @param $requestRoute
     * @return bool
     */
    private function requestRouteIsInIni($requestRoute)
    {
        return $this->routes[$requestRoute['route']] !== null;
    }

    /**
     * @param $requestRoute
     * @return bool|string
     */
    private function requestControllerAndMethodExist($requestRoute)
    {
        $requestController = $this->getControllerNameFromRequestRoute($requestRoute);
        $requestMethod = $this->getMethodNameFromRequestRoute($requestRoute);
        $requestClassFileName = $this->getControllerFileNameFromControllerName($requestController);

        // check if the controller exists
        if ( !file_exists($requestClassFileName)) {
            return false;
        }

        // check if the methods exists
        $methodExists = false;
        require_once $requestClassFileName;
        try {
            $refClass = new \ReflectionClass($requestController);
            $methodExists = $refClass->getMethod($requestMethod);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        if ( !$methodExists ) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed | array
     * gets routes from ini
     */
    private function getRoutes()
    {
        $routes = [];
        $routeDefinitions = parse_ini_file($this->routingConfigFile);
        foreach ( $routeDefinitions as $route => $definition) {
            $routes[$route] = $this->parseRouteDefinition($definition);
        }
        $this->routes = $routes;
    }

    /**
     * @param $definition
     * @return array
     */
    private function parseRouteDefinition($definition)
    {
        $parsedDefinition = explode('\\', $definition);
        return ['controller' => $parsedDefinition[0], 'method' => $parsedDefinition[1]];
    }

    /**
     * @param $requestRoute
     * @return mixed
     */
    private function getControllerNameFromRequestRoute($requestRoute)
    {
        return $this->routes[$requestRoute['route']]['controller'];
    }

    /**
     * @param $controllerName
     * @return string
     */
    private function getControllerFileNameFromControllerName($controllerName)
    {
        return '../controller/' . $controllerName . '.php';
    }

    /**
     * @param $requestRoute
     * @return mixed
     */
    private function getMethodNameFromRequestRoute($requestRoute)
    {
        return $this->routes[$requestRoute['route']]['method'];
    }
}