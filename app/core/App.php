<?php

namespace Cheapskate;
/**
 * Class App
 * @package Cheapskate
 */
class App {

    protected $requestController = 'ErrorController';
    protected $requestControllerFileName = '../controller/ErrorController.php';
    protected $requestMethod = 'error404';
    protected $requestParams = [];

    // will be filled with correct values in constructor
    // these are used to instantiate the controller
    protected $controller = '';
    protected $method = '';
    protected $params = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $Options = new AppOptions();

        $Router = new AppRouter();

        $requestUrl = $Router->parseUrl();

        // check if route is valid & controller + method exists
        if ( $Router->isValidRoute($requestUrl) )
        {
            $controllerClass = $Router->getClassParamsForInstance($requestUrl);
            $this->requestController = $controllerClass['controller'];
            $this->requestControllerFileName = $controllerClass['filename'];
            $this->requestMethod = $controllerClass['method'];
            $this->requestParams = array_values($requestUrl['params']);
        }

        // require the controller and create instance
        require_once $this->requestControllerFileName;
        $this->controller = new $this->requestController;
        $this->method = $this->requestMethod;
        $this->params = $this->requestParams;

        // call the method with request params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}