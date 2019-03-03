<?php

namespace PvPGNTracker\Views;

use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Servers as ServersModel;

class ServersHtml extends View {

    public function getMimeType() {
        return 'text/html;charset=utf-8';
    }

    public function render( Model &$model ) {
        if ( !$model instanceof ServersModel ) {
            throw new IncorrectModelException();
        }
        ( new Template( $model, 'Servers' ))->render();
    }

}
