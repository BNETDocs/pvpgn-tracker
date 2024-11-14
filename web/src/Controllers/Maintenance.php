<?php

namespace PvPGNTracker\Controllers;

class Maintenance extends Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\Maintenance();
    }

    public function invoke(?array $args): bool
    {
        $this->model->message = array_shift($args);
        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_SERVICE_UNAVAILABLE;
        return true;
    }
}
