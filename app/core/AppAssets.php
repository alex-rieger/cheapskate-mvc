<?php

namespace Cheapskate;

class AppAssets {
    protected $manifestFilePath = __DIR__ . '/../../public/build/manifest.json';
    protected $manifest;

    public function __construct()
    {
        $this->manifest = $this->getManifest();
    }

    public function getTwigExtension()
    {
        $asset = new \Twig_Function('asset', function ($name) {
            $this->findValueInManifest($name);
        });

        return $asset;
    }

    private function findValueInManifest($findKey)
    {
        foreach ($this->manifest as $key => $value) {
            if (strpos($key, $findKey) !== false) {
                echo $value;
            }
        }
    }

    private function getManifest()
    {
        return json_decode(file_get_contents($this->manifestFilePath), true);
    }
}