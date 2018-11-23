<?php

namespace Cheapskate;

/**
 * Class AppOptions
 * @package Cheapskate
 * Parses options from ini files
 */
class AppOptions {

    /**
     * @var string
     */
    protected $appConfigFile;

    /**
     * AppOptions constructor.
     */
    public function __construct()
    {
        $this->appConfigFile = $this->getAppConfigFile();
        $this->debugMode();
    }

    /**
     * @return int
     */
    static function getCacheTime() : int
    {
        $appConfig = self::parseAppConfig();
        return (int) $appConfig['caching_time_in_minutes'];
    }

    /**
     * @return array|bool
     */
    static function parseAppConfig()
    {
        return parse_ini_file(self::getAppConfigFile());
    }

    /**
     * @return string
     */
    static function getAppConfigFile()
    {
        return __DIR__ . '/../config/app.ini';
    }

    /**
     * turns on debug mode
     */
    private function debugMode()
    {
        $appConfig = $this->parseAppConfig();
        if ($appConfig['debug_mode'] == 1) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }
}