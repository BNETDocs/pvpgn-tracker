<?php

namespace PvPGNTracker\Libraries;

class VersionInfo
{
    const VERSION_INFO_FILE = '../etc/version';

    public static $version;

    /**
     * Block instantiation of this object.
     */
    private function __construct() {}

    public static function get(): array
    {
        $versions = [];

        $versions['pvpgntracker'] = self::getVersion();
        $versions['php']          = phpversion();

        return $versions;
    }

    private static function getVersion(): ?array
    {
        if (!file_exists(self::VERSION_INFO_FILE))
        {
            return null;
        }

        $buffer = file_get_contents(self::VERSION_INFO_FILE);

        if (empty($buffer))
        {
            return null;
        }

        return explode(PHP_EOL, $buffer);
    }
}
