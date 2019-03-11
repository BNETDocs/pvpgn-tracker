<?php

namespace PvPGNTracker\Controllers;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;
use \Locale;
use \PvPGNTracker\Libraries\UptimeConverter;
use \PvPGNTracker\Models\Servers as ServersModel;

class Servers extends Controller {
    const TF_SHUTDOWN = 0x00000001;
    const TF_PRIVATE  = 0x00000002;

    const FLAG_URL = '/images/flags/%s.png';

    public function &run( Router &$router, View &$view, array &$args ) {
        $model = new ServersModel();

        $state_file = Common::$config->tracker->state_file;

        if ( file_exists( $state_file ) && is_readable( $state_file )) {
            $bntrackd = file_get_contents( $state_file );
            $bntrackd = json_decode( $bntrackd, true );

            $model->servers = $bntrackd[ 'servers' ];
        } else {
            $model->servers = array();
        }

        self::normalizeServerList( $model->servers );

        $view->render( $model );

        $model->_responseCode = 200;
        $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
        $model->_responseTTL = 0;

        return $model;
    }

    protected static function filter_field( $value ) {
        return filter_var( $value, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    }

    protected static function normalizeServerList( &$servers ) {
        $new_servers = array();

        foreach( $servers as $key => $server ) {
            $flags = $server[ 'flags' ];

            if ( $flags & ( self::TF_SHUTDOWN | self::TF_PRIVATE ) > 0 ) {
                // Delist these servers
                continue;
            }

            // ---
            // Replace normal strings with html-safe strings:

            $server[ 'description' ] = self::filter_field(
                $server[ 'description' ]
            );

            $server[ 'contact_email' ] = self::filter_field(
                $server[ 'contact_email' ]
            );

            $server[ 'contact_name' ] = self::filter_field(
                $server[ 'contact_name' ]
            );

            $server[ 'software' ] = self::filter_field(
                $server[ 'software' ]
            );

            $server[ 'version' ] = self::filter_field(
                $server[ 'version' ]
            );

            $server[ 'platform' ] = self::filter_field(
                $server[ 'platform' ]
            );

            // ---
            // Convert uptime seconds to uptime relative string:

            $server[ 'uptime' ] = (
                new UptimeConverter( $server[ 'uptime' ])
            )->__tostring();

            // ---
            // Replace urls with valid html-safe urls, and only approve
            // http:// or https:// urls:

            $url = $server[ 'url' ];
            $proto = '';

            if ( substr( $url, 0, 8 ) == 'https://' ) {
                $proto = 'https://';
            } else if ( substr( $url, 0, 7 ) == 'http://' ) {
                $proto = 'http://';
            } else {
                $proto = '';
            }

            if ( !empty( $proto )) {
                $url = array(
                    self::filter_field( $url ),
                    self::filter_field( substr( $url, strlen( $proto ))),
                );
            } else {
                $url = array( self::filter_field( 'http://' . $url ));
                $url[ 1 ] = $url[ 0 ];
            }

            $server[ 'url' ] = $url;

            // ---
            // Setup the country flag on the location string:

            $ip_address = $server[ 'ip_address' ];
            $location = $server[ 'location' ];

            preg_match( '/^:([A-Z]{2})/', $location, $custom_flag_match );
            if ( isset( $custom_flag_match[ 1 ])) {
                $flag = $custom_flag_match[ 1 ];
                $location = substr( $location, 3 );
            } else {
                $flag = '';
            }

            $flag_file = __DIR__ . '/../static' . self::FLAG_URL;
            $flag_file = str_replace( '/', DIRECTORY_SEPARATOR, $flag_file);
            $flag_file = sprintf( $flag_file, strtolower( $flag ));

            if ( !file_exists( $flag_file )) {
                // their custom flag is unknown, let us figure it out then:
                $flag = '';
            }

            if ( empty( $flag )) {
                // use geoip information:
                $flag = geoip_country_code_by_name( $ip_address );
                $country_name = geoip_country_name_by_name( $ip_address );
            } else {
                // their custom flag exists, set country name:
                $country_name = Locale::getDisplayRegion( '-' . $flag, 'en' );
            }

            if ( empty( $flag )) {
                // geoip could not find it either, use pirate flag:
                $flag = 'pirate';
                $country_name = 'Unknown';
            }

            $server[ 'country_flag' ] = array(
                Common::relativeUrlToAbsolute(
                    sprintf( self::FLAG_URL, strtolower( $flag ))
                ),
                strtoupper( $flag ),
                $country_name,
            );

            // ---
            // with country information from earlier, setup the location:

            $location = trim( $location );

            if ( empty( $location ) && !empty( $country_name )) {
                $location = $country_name;
            } else if ( empty( $location )) {
                $location = 'Unknown';
            }

            $server[ 'location' ] = self::filter_field( $location );

            // ---
            // insert server back into the list

            $new_servers[ $key ] = $server;
        }

        $servers = $new_servers;
    }
}
