<?php

namespace App;

class Build {

    public static function get() {
        return "5.1.5.1";
    }

    /**
     * If this returns true, $asset() and $css() will change prefix to /{prefix}/dist (which includes obfuscated files).
     */
    public static function isProduction() {
        return true;
    }

}
