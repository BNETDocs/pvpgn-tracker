<?php

namespace PvPGNTracker\Controllers;

use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Servers as ServersModel;

class Servers extends Controller {

    public function &run( Router &$router, View &$view, array &$args ) {

        $key_prefix = Common::$config->memcache->key_prefix;
        $key_suffix = Common::$config->memcache->key_suffix;

        $model          = new ServersModel();
        $model->servers = array();

        $cache_key   = $key_prefix . 'servers' . $key_suffix;
        $cache_value = Common::$cache->get( $cache_key );

        if ( is_string( $cache_value )) {

            $keys = explode( ',', $cache_value );

            foreach ( $keys as $key ) {
                $cache_key_2   = $key_prefix . 'server-' . $key . $key_suffix;
                $cache_value_2 = Common::$cache->get( $cache_key_2 );

                if ( $cache_value_2 ) {
                    $model->servers[] = unserialize( $cache_value_2 );
                }
            }

        }

        $view->render( $model );

        $model->_responseCode = 200;
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 0;

        return $model;

    }

}
