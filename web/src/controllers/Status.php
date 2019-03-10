<?php

namespace PvPGNTracker\Controllers;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\DateTime;
use \CarlBennett\MVC\Libraries\GeoIP;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;

use \PvPGNTracker\Libraries\VersionInfo;
use \PvPGNTracker\Models\Status as StatusModel;

use \DateTimeZone;
use \Exception;
use \StdClass;

class Status extends Controller {

    /**
     * run()
     *
     * @return Model The model with data that can be rendered by a view.
     */
    public function &run( Router &$router, View &$view, array &$args ) {

        $model = new StatusModel();
        $code  = ( self::getStatus( $model ) ? 200 : 500 );

        $view->render( $model );

        $model->_responseCode = $code;
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 300;

        return $model;

    }

    /**
     * getStatus()
     *
     * @return bool Indicates summary health status, where true is healthy.
     */
    protected static function getStatus( StatusModel &$model ) {
        $status = new StdClass();

        $state_file = Common::$config->tracker->state_file;

        $healthcheck = new StdClass();

        $healthcheck->bntrackd = array(
          'exists'   => file_exists( $state_file ),
          'readable' => null,
          'size'     => null,
          'valid'    => null,
        );

        if ( $healthcheck->bntrackd[ 'exists' ]) {
            $healthcheck->bntrackd[ 'readable' ] = is_readable( $state_file );
            $healthcheck->bntrackd[ 'size' ] = filesize( $state_file );

            try {
                $json = file_get_contents( $state_file );
                $json = json_decode( $json, true );
                $e = json_last_error();
                $healthcheck->bntrackd[ 'valid' ] = (
                    ( $e === JSON_ERROR_NONE ? (
                            $json ? true : false
                        ) : array(
                            $e, json_last_error_msg()
                        )
                    )
                );
            } catch ( Exception $e ) {
                /* JSON_THROW_ON_ERROR works on PHP >= 7.3 */
                $healthcheck->bntrackd[ 'valid' ] = array(
                    $e->getCode(), get_class( $e )
                );
            }
        }

        $utc = new DateTimeZone( 'Etc/UTC' );

        $status->healthcheck    = $healthcheck;
        $status->remote_address = getenv( 'REMOTE_ADDR' );
        $status->remote_geoinfo = GeoIP::get( $status->remote_address );
        $status->timestamp      = new DateTime( 'now', $utc );
        $status->version_info   = VersionInfo::$version;

        $model->status = $status;

        foreach ( $healthcheck as $key => $val ) {
            if (is_bool($val) && !$val) {
                // let the controller know that we're unhealthy.
                return false;
            }
        }

        return true;
    }

}
