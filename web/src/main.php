<?php

namespace PvPGNTracker;

use \PvPGNTracker\Libraries\Config;
use \PvPGNTracker\Libraries\Router;
use \PvPGNTracker\Libraries\VersionInfo;

function main()
{
  if (!file_exists( __DIR__ . '/../lib/autoload.php'))
  {
    http_response_code(500);
    exit('Server misconfigured. Please run `composer install`.');
  }
  require(__DIR__ . '/../lib/autoload.php');

  date_default_timezone_set('Etc/UTC');

  Config::load();

  \PvPGNTracker\Libraries\ExceptionHandler::register();
  VersionInfo::$version = VersionInfo::get();

  if (Config::$root['maintenance']['enable'])
  {
    Router::$routes = [
      ['#.*#', 'Maintenance', ['MaintenanceHtml'], Config::$root['maintenance']['message']],
    ];
  }
  else
  {
    Router::$routes = [
      ['#^/$#', 'RedirectSoft', ['RedirectSoftHtml'], '/servers'],
      ['#^/search/?$#', 'Search', ['SearchHtml']],
      ['#^/server/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5})/?.*\.html?$#', 'Server\\View', ['Server\\ViewHtml']],
      ['#^/server/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5})/?.*\.json$#', 'Server\\View', ['Server\\ViewJson']],
      ['#^/server/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5})/?.*\.txt$#', 'Server\\View', ['Server\\ViewPlain']],
      ['#^/server/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5})/?#', 'Server\\View', ['Server\\ViewHtml', 'Server\\ViewJson', 'Server\\ViewPlain']],
      ['#^/servers/?$#', 'Servers', ['ServersHtml']],
      ['#^/servers\.json$#', 'Servers', ['ServersJson']],
      ['#^/status/?$#', 'RedirectSoft', ['RedirectSoftHtml'], '/status.json'],
      ['#^/status\.json$#', 'Status', ['StatusJson']],
      ['#^/status\.txt$#', 'Status', ['StatusPlain']],
    ];
    Router::$route_not_found = ['PageNotFound', ['PageNotFoundHtml', 'PageNotFoundJson', 'PageNotFoundPlain']];
  }

  Router::invoke();
}

main();
