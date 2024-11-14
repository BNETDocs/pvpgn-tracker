<?php

namespace PvPGNTracker\Controllers;

use \PvPGNTracker\Libraries\HttpCode;

class Status extends Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\Status();
    }

    public function invoke(?array $args): bool
    {
        $code = $this->getStatus() ? HttpCode::HTTP_OK : HttpCode::HTTP_INTERNAL_SERVER_ERROR;
        $this->model->_responseCode = $code;
        return true;
    }

    protected function getStatus(): bool
    {
        $status = &$this->model->status;

        $state_file = \PvPGNTracker\Libraries\Config::$root['tracker']['state_file'];

        $healthcheck = [];

        $healthcheck['bntrackd'] = [
          'file'     => $state_file,
          'exists'   => file_exists($state_file),
          'readable' => null,
          'size'     => null,
          'valid'    => null,
        ];

        if ($healthcheck['bntrackd']['exists'])
        {
            $healthcheck['bntrackd']['readable'] = is_readable($state_file);
            $healthcheck['bntrackd']['size'] = filesize($state_file);

            try {
                $json = file_get_contents($state_file);
                $json = json_decode($json, true, 512, JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
                $e = json_last_error();
                $healthcheck['bntrackd']['valid'] = (
                    $e === JSON_ERROR_NONE ? ($json ? true : false) : [$e, json_last_error_msg()]
                );
            }
            catch (\JsonException $e)
            {
                $healthcheck['bntrackd']['valid'] = [$e->getCode(), get_class($e)];
            }
        }

        $status['healthcheck']    = $healthcheck;
        $status['remote_address'] = getenv('REMOTE_ADDR');
        $status['remote_geoinfo'] = \PvPGNTracker\Libraries\GeoIP::getRecord($status['remote_address']);
        $status['timestamp']      = new \PvPGNTracker\Libraries\DateTime('now');
        $status['version_info']   = \PvPGNTracker\Libraries\VersionInfo::$version;

        foreach ($healthcheck as $key => $val)
        {
            if (is_bool($val) && !$val)
            {
                // let the controller know that we're unhealthy.
                return false;
            }
        }

        return true;
    }
}
