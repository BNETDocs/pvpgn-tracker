<?php

namespace PvPGNTracker\Libraries;

use \StdClass;

class VersionInfo {

    const VERSION_INFO_FILE = '../etc/version';

    public static $version;

    /**
     * Block instantiation of this object.
     */
    private function __construct() {}

    public static function get() {
        $versions = new StdClass();

        $versions->pvpgntracker = self::getVersion();
        $versions->php          = phpversion();

        return $versions;
    }

    private static function getVersion() {
        if ( !file_exists( self::VERSION_INFO_FILE ) ) {
            return null;
        }

        $buffer = file_get_contents( self::VERSION_INFO_FILE );

        if ( empty( $buffer ) ) {
            return null;
        }

        $buffer = explode( PHP_EOL, $buffer );

        return $buffer;
    }

}
