<?php

namespace PvPGNTracker\Controllers;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Solicit as SolicitModel;

class Solicit extends Controller {

    public function &run( Router &$router, View &$view, array &$args ) {

        $model = new SolicitModel();

        $method = $router->getRequestMethod();

        $request = (
            $method == 'POST' ? $router->getRequestBodyArray() : null
        );

        $model->response = array(
            'request' => $request,
            'result'  => [ 500, 'Internal error' ],
        );

        $model->response[ 'result' ] = $this->getResponse(
            $method, $model, $request
        );

        $view->render( $model );

        $model->_responseCode = $model->response[ 'result' ][ 0 ];
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 0;

        return $model;

    }

    private function getResponse( $method, SolicitModel &$model, $request ) {
        if ( $method != 'POST' ) {
            return array( 405, 'Method Not Allowed' );
        }

        if ( !$request ) {
            return array( 400, 'Invalid request' );
        }

        if ( !isset( $request->message )) {
            return array( 400, 'Missing field: message' );
        }

        $message = $request->message;

        if ( !isset( $message->server_address )) {
            return array( 400, 'Missing message field: server_address' );
        }
        if ( !isset( $message->server_port )) {
            return array( 400, 'Missing message field: server_port' );
        }
        if ( !isset( $message->software )) {
            return array( 400, 'Missing message field: software' );
        }
        if ( !isset( $message->version )) {
            return array( 400, 'Missing message field: version' );
        }
        if ( !isset( $message->platform )) {
            return array( 400, 'Missing message field: platform' );
        }
        if ( !isset( $message->server_description )) {
            return array( 400, 'Missing message field: server_description' );
        }
        if ( !isset( $message->server_location )) {
            return array( 400, 'Missing message field: server_location' );
        }
        if ( !isset( $message->server_url )) {
            return array( 400, 'Missing message field: server_url' );
        }
        if ( !isset( $message->contact_name )) {
            return array( 400, 'Missing message field: contact_name' );
        }
        if ( !isset( $message->contact_email )) {
            return array( 400, 'Missing message field: contact_email' );
        }
        if ( !isset( $message->active_users )) {
            return array( 400, 'Missing message field: active_users' );
        }
        if ( !isset( $message->active_channels )) {
            return array( 400, 'Missing message field: active_channels' );
        }
        if ( !isset( $message->active_games )) {
            return array( 400, 'Missing message field: active_games' );
        }
        if ( !isset( $message->uptime )) {
            return array( 400, 'Missing message field: uptime' );
        }
        if ( !isset( $message->total_games )) {
            return array( 400, 'Missing message field: total_games' );
        }
        if ( !isset( $message->total_logins )) {
            return array( 400, 'Missing message field: total_logins' );
        }

        $msg = array(
            'server_address'     => $message->server_address,
            'server_port'        => $message->server_port,
            'software'           => $message->software,
            'version'            => $message->version,
            'platform'           => $message->platform,
            'server_description' => $message->server_description,
            'server_location'    => $message->server_location,
            'server_url'         => $message->server_url,
            'contact_name'       => $message->contact_name,
            'contact_email'      => $message->contact_email,
            'active_users'       => $message->active_users,
            'active_channels'    => $message->active_channels,
            'active_games'       => $message->active_games,
            'uptime'             => $message->uptime,
            'total_games'        => $message->total_games,
            'total_logins'       => $message->total_logins,
        );

        $msg[ 'uuid' ] = hash( 'sha1', (
            $message->server_address . $message->server_port
        ));

        $key_prefix = Common::$config->memcache->key_prefix;
        $key_suffix = Common::$config->memcache->key_suffix;

        $cache_key   = $key_prefix . 'servers' . $key_suffix;
        $cache_value = Common::$cache->get( $cache_key );

        $keys = array();

        if ( is_string( $cache_value )) {
            $keys = explode( ',', $cache_value );
        }

        $keys[] = $msg[ 'uuid' ];

        $cache_value = implode( ',', $keys );

        Common::$cache->set(
            $key_prefix . 'server-' . $msg[ 'uuid' ] . $key_suffix,
            serialize( $msg ),
            900
          );

        Common::$cache->set( $cache_key, $cache_value, 900 );

        return array( 200, 'Success' );
    }

}
