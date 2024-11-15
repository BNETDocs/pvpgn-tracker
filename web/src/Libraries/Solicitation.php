<?php

namespace PvPGNTracker\Libraries;

class Solicitation implements \JsonSerializable
{
    // -- PvPGN Solicit UDP Datagram Format --
    // ## All ints are little-endian ##
    // (UINT16)      Packet Version (0x0002)
    // (UINT16)      Server Port
    // (UINT32)      Flags
    //  (UINT8) [32] Software
    //  (UINT8) [16] Version
    //  (UINT8) [32] Platform
    //  (UINT8) [64] Server Description
    //  (UINT8) [64] Server Location
    //  (UINT8) [96] Server URL
    //  (UINT8) [64] Contact Name
    //  (UINT8) [64] Contact Email
    // (UINT32)      Active Users
    // (UINT32)      Active Channels
    // (UINT32)      Active Games
    // (UINT32)      Uptime
    // (UINT32)      Total Games
    // (UINT32)      Total Logins
    // -- End --

    public const FLAG_URL    = '/images/flags/%s.png';
    public const TF_SHUTDOWN = 0x00000001;
    public const TF_PRIVATE  = 0x00000002;

    public int $active_channels = 0;
    public int $active_games = 0;
    public int $active_users = 0;
    public string $contact_email = '';
    public string $contact_name = '';
    public array $country_flag = [];
    public int $flags = 0;
    public string $platform = '';
    public string $server_address = '';
    public string $server_description = '';
    public string $server_location = '';
    public int $server_port = 0;
    public array $server_url = ['', ''];
    public string $software = '';
    public int $total_games = 0;
    public int $total_logins = 0;
    public string $uptime = '';
    public string $version = '';

    public function __construct(?array $args = null)
    {
        if (!$args) return;

        $this->active_channels = $args['active_channels'];
        $this->active_games = $args['active_games'];
        $this->active_users = $args['active_users'];
        $this->contact_email = $args['contact_email'];
        $this->contact_name = $args['contact_name'];
        $this->country_flag = $args['country_flag'] ?? [];
        $this->flags = $args['flags'];
        $this->platform = $args['platform'];
        $this->server_address = $args['ip_address'];
        $this->server_description = $args['description'];
        $this->server_location = $args['location'];
        $this->server_port = $args['port'];
        $this->server_url = $args['url'];
        $this->software = $args['software'];
        $this->total_games = $args['total_games'];
        $this->total_logins = $args['total_logins'];
        $this->uptime = $args['uptime'];
        $this->version = $args['version'];
    }

    public function jsonSerialize(): mixed
    {
        return [
            'country_flag'       => $this->country_flag,
            'server_address'     => $this->server_address,
            'server_port'        => $this->server_port,
            'software'           => $this->software,
            'version'            => $this->version,
            'platform'           => $this->platform,
            'server_description' => $this->server_description,
            'server_location'    => $this->server_location,
            'server_url'         => $this->server_url,
            'contact_name'       => $this->contact_name,
            'contact_email'      => $this->contact_email,
            'active_users'       => $this->active_users,
            'active_channels'    => $this->active_channels,
            'active_games'       => $this->active_games,
            'uptime'             => $this->uptime,
            'total_games'        => $this->total_games,
            'total_logins'       => $this->total_logins,
        ];
    }

    public static function normalizeList(array &$servers): void
    {
        $new_servers = [];

        foreach ($servers as $key => $server)
        {
            $flags = (int) $server['flags'];

            if ($flags & (self::TF_SHUTDOWN | self::TF_PRIVATE) > 0)
            {
                // Delist these servers
                continue;
            }

            // ---
            // Validate contact email

            if ($server['contact_email'] == 'unknown')
            {
                // this is the default value from PvPGN, instead use empty.
                $server['contact_email'] = '';
            }

            $server['contact_email'] = filter_var($server['contact_email'], FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);

            // ---
            // Replace normal strings with html-safe strings:

            $server['description'] = filter_var($server['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $server['contact_email'] = filter_var($server['contact_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $server['contact_name'] = filter_var($server['contact_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $server['software'] = filter_var($server['software'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $server['version'] = filter_var($server['version'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $server['platform'] = filter_var($server['platform'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // ---
            // Convert uptime seconds to uptime relative string:

            $server['uptime'] = (new \PvPGNTracker\Libraries\UptimeConverter($server['uptime']))->__tostring();

            // ---
            // Replace urls with valid html-safe urls, and only approve
            // http:// or https:// urls:

            $url = $server['url'];
            $proto = '';

            if (substr($url, 0, 8) == 'https://')
            {
                $proto = 'https://';
            }
            else if (substr($url, 0, 7) == 'http://')
            {
                $proto = 'http://';
            }
            else
            {
                $proto = '';
            }

            if (!empty($proto))
            {
                $url = [
                    filter_var($url, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    filter_var(substr($url, strlen($proto)), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                ];
            }
            else
            {
                $url = [filter_var('http://' . $url, FILTER_SANITIZE_FULL_SPECIAL_CHARS)];
                $url[1] = $url[0];
            }

            $server['url'] = $url;

            // ---
            // Setup the country flag on the location string:

            $ip_address = $server['ip_address'];
            $location = $server['location'];

            preg_match('/^:([A-Z]{2})/', $location, $custom_flag_match);
            if (isset($custom_flag_match[1]))
            {
                $flag = $custom_flag_match[1];
                $location = substr($location, 3);
            }
            else
            {
                $flag = '';
            }

            $flag_file = __DIR__ . '/../Static' . self::FLAG_URL;
            $flag_file = str_replace('/', DIRECTORY_SEPARATOR, $flag_file);
            $flag_file = sprintf($flag_file, strtolower($flag));

            if (!file_exists($flag_file))
            {
                // their custom flag is unknown, let us figure it out then:
                $flag = '';
            }

            if (empty($flag))
            {
                // use geoip information:
                $country = \PvPGNTracker\Libraries\GeoIP::getRecord($ip_address)->country;
                $flag = $country->isoCode;
                $country_name = $country->names['en'];
            }
            else
            {
                // their custom flag exists, set country name:
                $country_name = \Locale::getDisplayRegion('-' . $flag, 'en');
            }

            if (empty($flag))
            {
                // geoip could not find it either, use pirate flag:
                $flag = 'pirate';
                $country_name = 'Unknown';
            }

            $server['country_flag'] = [
                \PvPGNTracker\Libraries\UrlFormatter::format(sprintf(self::FLAG_URL, strtolower($flag))),
                strtoupper($flag),
                $country_name,
            ];

            // ---
            // with country information from earlier, setup the location:

            $location = trim($location);

            if (empty($location) && !empty($country_name))
            {
                $location = $country_name;
            }
            else if (empty($location))
            {
                $location = 'Unknown';
            }

            $server['location'] = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // ---
            // insert server back into the list

            $new_servers[$key] = $server;
        }

        $servers = $new_servers;
    }
}
