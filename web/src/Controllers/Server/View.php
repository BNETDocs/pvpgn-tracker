<?php

namespace PvPGNTracker\Controllers\Server;

use \PvPGNTracker\Libraries\HttpCode;
use PvPGNTracker\Libraries\Solicitation;

class View extends \PvPGNTracker\Controllers\Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\Server\View();
    }

    public function invoke(?array $args): bool
    {
        $this->model->solicitation = null;
        $server_address_and_port = array_shift($args);
        $state_file = \PvPGNTracker\Libraries\Config::$root['tracker']['state_file'];

        if (file_exists($state_file) && is_readable($state_file))
        {
            $bntrackd = file_get_contents($state_file);
            $bntrackd = json_decode($bntrackd, true);
            $servers = $bntrackd['servers'];
            \PvPGNTracker\Libraries\Solicitation::normalizeList($servers);
            if (isset($servers[$server_address_and_port]))
            {
                $this->model->solicitation = new Solicitation($servers[$server_address_and_port]);
            }
        }
        $this->model->_responseCode = $this->model->solicitation ? HttpCode::HTTP_OK : HttpCode::HTTP_NOT_FOUND;
        return true;
    }
}