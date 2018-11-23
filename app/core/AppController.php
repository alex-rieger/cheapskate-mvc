<?php

namespace Cheapskate;

/**
 * Class AppController
 * @package Cheapskate
 * Main Controller that holds Twig, Cache und Inject methods
 */
class AppController
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var AppCache
     */
    protected $cache;

    /**
     * AppController constructor.
     */
    public function __construct()
    {
        // init twig
        $loader = new \Twig_Loader_Filesystem('../templates');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => '../app/cache/twig',
            'debug' => true,
            'auto_reload' => true
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());

        // register Twig Extensions
        $assetExtension = new AppAssets();
        $this->twig->addFunction($assetExtension->getTwigExtension());

        // init cache
        $this->cache = new AppCache();
    }

    /**
     * @param int $httpStatusCode
     */
    protected function response($httpStatusCode = 200)
    {
        http_response_code($httpStatusCode);
    }

    /**
     * @param string $view
     * @param array $data
     * @param int $response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $view, array $data = [], $response = 200) : void
    {
        $this->response($response);
        echo $this->twig->render($view . '.html.twig', $data);
    }

    /**
     * @param string $type
     * @param string $class
     * @return object | bool
     */
    protected function inject(string $type, string $class)
    {
        $file = __DIR__ . '/../../' . $type . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $class;
        }

        return false;
    }
}