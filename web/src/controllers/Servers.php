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

        $state_file = Common::$config->tracker->state_file;

        if ( file_exists( $state_file ) && is_readable( $state_file )) {
            $bntrackd = file_get_contents( $state_file );
            $bntrackd = json_decode( $bntrackd, true );

            $model->servers = $bntrackd[ 'servers' ];
        } else {
            $model->servers = null;
        }

        $view->render( $model );

        $model->_responseCode = 200;
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 0;

        return $model;
    }
}
