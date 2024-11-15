<?php

namespace PvPGNTracker\Controllers;

class Servers extends Controller
{
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

        \PvPGNTracker\Libraries\Solicitation::normalizeList($this->model->servers);

        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_OK;
        return true;
    }
}
