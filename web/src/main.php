<?php

namespace PvPGNTracker;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\GlobalErrorHandler;
use \CarlBennett\MVC\Libraries\Router;
use \PvPGNTracker\Libraries\VersionInfo;

function main() {

  if ( !file_exists( __DIR__ . '/../lib/autoload.php' )) {
    http_response_code( 500 );
    exit( 'Server misconfigured. Please run `composer install`.' );
  }
  require( __DIR__ . '/../lib/autoload.php' );

  GlobalErrorHandler::createOverrides();

  date_default_timezone_set('UTC');

  Common::$config = json_decode(file_get_contents(
    __DIR__ . '/../etc/config.json'
  ));

  VersionInfo::$version = VersionInfo::get();

  $router = new Router(
    'PvPGNTracker\\Controllers\\',
    'PvPGNTracker\\Views\\'
  );

  if ( Common::$config->maintenance->enable ) {
    $router->addRoute( // URL: *
      '#.*#', 'Maintenance', 'MaintenanceHtml',
      Common::$config->maintenance->message
    );
  } else {
    $router->addRoute( // URL: /
      '#^/$#', 'RedirectSoft', 'RedirectSoftHtml', '/servers'
    );
    $router->addRoute( // URL: /search
      '#^/search/?$#', 'Search', 'SearchHtml'
    );
    $router->addRoute( // URL: /server/:id.json
      '#^/server/(\d+)/?.*\.json$#', 'Server\\View', 'Server\\ViewJSON'
    );
    $router->addRoute( // URL: /server/:id.txt
      '#^/server/(\d+)/?.*\.txt$#', 'Server\\View', 'Server\\ViewPlain'
    );
    $router->addRoute( // URL: /server/:id
      '#^/server/(\d+)/?#', 'Server\\View', 'Server\\ViewHtml'
    );
    $router->addRoute( // URL: /servers
      '#^/servers/?$#', 'Servers', 'ServersHtml'
    );
    $router->addRoute( // URL: /servers.json
      '#^/servers\.json$#', 'Servers', 'ServersJSON'
    );
    $router->addRoute( // URL: /status
      '#^/status/?$#', 'RedirectSoft', 'RedirectSoftHtml', '/status.json'
    );
    $router->addRoute( // URL: /status.json
      '#^/status\.json$#', 'Status', 'StatusJSON'
    );
    $router->addRoute( // URL: /status.txt
      '#^/status\.txt$#', 'Status', 'StatusPlain'
    );
    $router->addRoute('#.*#', 'PageNotFound', 'PageNotFoundHtml'); // URL: *
  }

  $router->route();
  $router->send();

}

main();
