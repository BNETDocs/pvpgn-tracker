<?php

namespace PvPGNTracker\Controllers;

class Servers extends Controller
{
    public const TF_SHUTDOWN = 0x00000001;
    public const TF_PRIVATE  = 0x00000002;

    public const FLAG_URL = '/images/flags/%s.png';

    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\Servers();
    }

    public function invoke(?array $args): bool
    {
        $state_file = \PvPGNTracker\Libraries\Config::$root['tracker']['state_file'];

        if (file_exists($state_file) && is_readable($state_file))
        {
            $bntrackd = file_get_contents($state_file);
            $bntrackd = json_decode($bntrackd, true);
            $this->model->servers = $bntrackd['servers'];
        }
        else
        {
            $this->model->servers = [];
        }

        $this->normalizeServerList();

        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_OK;
        return true;
    }

    protected function normalizeServerList(): void
    {
        $servers = &$this->model->servers;
        $new_servers = [];

        foreach ($servers as $key => $server)
        {
            $flags = $server[ 'flags' ];

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

            $server['contact_email'] = filter_var(
                $server['contact_email'], FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMAIL_UNICODE
            );

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

            $flag_file = __DIR__ . '/../static' . self::FLAG_URL;
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
