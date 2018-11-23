<?php

namespace Cheapskate;

/**
 * Class AppCache
 * @package BudgieMVC
 * Simple file based caching. Uses JSON format.
 * Creates and reads from "./app/cache/application"
 */
class AppCache {
    protected $cacheTime;
    protected $cacheDir = __DIR__ . '/../cache/application';
    protected $cacheContent;
    protected $cacheFile = false;
    protected $cacheTimestamp;

    public function __construct()
    {
        $this->cacheTime = $this->setCacheTime();
        $this->cacheTimestamp = $this->initCacheTimestamp();
        $this->cacheFile = $this->initCacheFile();
        $this->cacheContent = $this->initCacheContent();
    }

    public function read($key)
    {
        return $this->cacheContent[$key];
    }

    public function write($key, $value, $overwriteDuplicate = false)
    {
        if ( !$overwriteDuplicate && $this->read($key) !== null ) {
            return false;
        }

        $this->cacheContent[$key] = $value;
        $this->save();

        return true;
    }

    private function save()
    {
        file_put_contents($this->cacheFile, json_encode($this->cacheContent));
    }

    private function initCacheFile()
    {
        foreach ( glob($this->cacheDir . '/*') as $file ) {
            if ($file !== '.' &&
                $file !== '..' &&
                $file !== '.gitignore' &&
                filemtime($file) > (time() - 60 * $this->cacheTime)
            ) {
                return $file;
            }
        }

        if (!$this->cacheFile) {
            $this->cleanCacheDir();
            return $this->createCacheFile();
        }

        return false;
    }

    private function initCacheContent()
    {
        if (!$this->cacheFile) {
            return [];
        }

        return json_decode(file_get_contents($this->cacheFile), true);
    }

    private function cleanCacheDir()
    {
        foreach ( glob($this->cacheDir . '/*') as $file ) {
            if ($file !== '.' && $file !== '..' & $file !== '.gitignore') {
                unlink($file);
            }
        }
    }

    private function createCacheFile()
    {
        $cacheFileTimestamp = $this->initCacheTimestamp();
        $cacheFile = $this->cacheDir . '/'. $cacheFileTimestamp;
        file_put_contents($cacheFile, '{}');
        return $cacheFile;
    }

    private function setCacheTime()
    {
        return AppOptions::getCacheTime();
    }

    private function initCacheTimestamp()
    {
        return time();
    }
}