<?php

namespace PvPGNTracker\Controllers;

class Search extends Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\Search();
    }

    public function invoke(?array $args): bool
    {
        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_OK;
        return true;
    }
}
