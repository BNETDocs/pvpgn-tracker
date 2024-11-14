<?php

namespace PvPGNTracker\Libraries;

class Config
{
    public static array $root = [];

    public static function load(string $filename = __DIR__ . '/../../etc/config.json'): bool
    {
        $contents = file_get_contents($filename);
        if (!is_string($contents)) return false;
        self::$root = json_decode($contents, true, 512, JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
        return true;
    }

    public static function unload(): void
    {
        self::$root = [];
    }
}
