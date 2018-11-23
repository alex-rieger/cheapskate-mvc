<?php

namespace Cheapskate;

class AppUtils {

    static function dot($array, $path)
    {
        $location = &$array;
        foreach ( explode('.', $path) as $step ) {
            $location = &$location[$step];
        }
        return $location;
    }

}