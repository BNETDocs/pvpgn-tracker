<?php

namespace PvPGNTracker\Controllers;

class PageNotFound extends Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\PageNotFound();
    }

    public function invoke(?array $args): bool
    {
        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_NOT_FOUND;
        return true;
    }
}
