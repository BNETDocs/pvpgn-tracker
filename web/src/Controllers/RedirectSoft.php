<?php

namespace PvPGNTracker\Controllers;

class RedirectSoft extends Controller
{
    public function __construct()
    {
        $this->model = new \PvPGNTracker\Models\RedirectSoft();
    }

    public function invoke(?array $args): bool
    {
        $this->model->location = array_shift($args);
        $this->model->_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_FOUND;
        $this->model->_responseHeaders['Location'] = $this->model->location;
        return true;
    }
}
