<?php

namespace PvPGNTracker\Controllers;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Servers as ServersModel;

class Servers extends Controller {
    public function &run( Router &$router, View &$view, array &$args ) {
        $model = new ServersModel();

        $bntrackd = file_get_contents( Common::$config->tracker->state_file );
        $bntrackd = json_decode( $bntrackd, true );

        $model->servers = $bntrackd[ 'servers' ];

        $view->render( $model );

        $model->_responseCode = 200;
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 0;

        return $model;
    }
}
